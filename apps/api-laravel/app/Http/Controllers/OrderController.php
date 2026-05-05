<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Create a new order and initiate payment
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string',
            'items.*.price' => 'required|integer|min:0',
            'payment_method' => 'required|string|in:qris,bni_va,bri_va,cimb_niaga_va,maybank_va,permata_va,bnc_va,atm_bersama_va,sampoerna_va,artha_graha_va',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate total amount
        $amount = 0;
        foreach ($validated['items'] as $item) {
            $amount += $item['qty'] * $item['price'];
        }

        if ($amount < 1000) {
            return response()->json(['error' => 'Minimum order Rp 1.000'], 422);
        }

        // Generate unique order ID
        $orderId = 'LB-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Create order in database
        $order = Order::create([
            'order_id' => $orderId,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'items' => $validated['items'],
            'amount' => $amount,
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Call Pakasir API to create transaction
        try {
            $pakasirProject = config('pakasir.project');
            $pakasirApiKey = config('pakasir.api_key');

            if (!$pakasirProject || !$pakasirApiKey) {
                Log::error('Pakasir credentials not configured');
                return response()->json(['error' => 'Payment gateway belum dikonfigurasi'], 500);
            }

            $response = Http::post("https://app.pakasir.com/api/transactioncreate/{$validated['payment_method']}", [
                'project' => $pakasirProject,
                'order_id' => $orderId,
                'amount' => $amount,
                'api_key' => $pakasirApiKey,
            ]);

            if ($response->successful()) {
                $paymentData = $response->json('payment');

                $order->update([
                    'fee' => $paymentData['fee'] ?? 0,
                    'total_payment' => $paymentData['total_payment'] ?? $amount,
                    'payment_number' => $paymentData['payment_number'] ?? null,
                    'expired_at' => $paymentData['expired_at'] ?? now()->addHours(24),
                    'status' => 'waiting_payment',
                ]);

                return response()->json([
                    'order' => $order->fresh(),
                    'payment' => [
                        'method' => $validated['payment_method'],
                        'payment_number' => $paymentData['payment_number'] ?? null,
                        'amount' => $amount,
                        'fee' => $paymentData['fee'] ?? 0,
                        'total_payment' => $paymentData['total_payment'] ?? $amount,
                        'expired_at' => $paymentData['expired_at'] ?? null,
                    ],
                ], 201);
            } else {
                Log::error('Pakasir API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_id' => $orderId,
                ]);

                $order->update(['status' => 'cancelled']);

                return response()->json([
                    'error' => 'Gagal membuat transaksi pembayaran. Silakan coba lagi.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'error' => 'Terjadi kesalahan saat membuat pembayaran.',
            ], 500);
        }
    }

    /**
     * Webhook handler for Pakasir payment notifications
     */
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::info('Pakasir webhook received', $data);

        $orderId = $data['order_id'] ?? null;
        $amount = $data['amount'] ?? null;
        $status = $data['status'] ?? null;

        if (!$orderId || !$amount) {
            return response()->json(['error' => 'Invalid webhook data'], 400);
        }

        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            Log::warning('Webhook received for unknown order', ['order_id' => $orderId]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Verify amount matches
        if ((int) $amount !== $order->amount) {
            Log::warning('Webhook amount mismatch', [
                'order_id' => $orderId,
                'expected' => $order->amount,
                'received' => $amount,
            ]);
            return response()->json(['error' => 'Amount mismatch'], 400);
        }

        // Verify via Pakasir API for extra security
        $verified = $this->verifyTransaction($orderId, $order->amount);

        if ($verified && $status === 'completed') {
            $order->update([
                'status' => 'paid',
                'paid_at' => $data['completed_at'] ?? now(),
            ]);

            Log::info('Order marked as paid', ['order_id' => $orderId]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Check order status
     */
    public function status(Request $request, string $orderId)
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        // Check if expired
        if ($order->isExpired()) {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json([
            'order' => $order,
        ]);
    }

    /**
     * List all orders (admin)
     */
    public function index(Request $request)
    {
        $query = Order::query()->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate($request->get('per_page', 20));

        return response()->json($orders);
    }

    /**
     * Update order status (admin)
     */
    public function updateStatus(Request $request, string $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,waiting_payment,paid,processing,shipped,completed,cancelled',
        ]);

        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        $order->update(['status' => $validated['status']]);

        return response()->json(['order' => $order->fresh()]);
    }

    /**
     * Cancel an order and cancel pakasir transaction
     */
    public function cancel(Request $request, string $orderId)
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        if ($order->isPaid()) {
            return response()->json(['error' => 'Order sudah dibayar, tidak bisa dibatalkan'], 422);
        }

        // Cancel on Pakasir
        try {
            $pakasirProject = config('pakasir.project');
            $pakasirApiKey = config('pakasir.api_key');

            Http::post('https://app.pakasir.com/api/transactioncancel', [
                'project' => $pakasirProject,
                'order_id' => $orderId,
                'amount' => $order->amount,
                'api_key' => $pakasirApiKey,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to cancel Pakasir transaction', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json(['order' => $order->fresh()]);
    }

    /**
     * Get order statistics (admin dashboard)
     */
    public function stats()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_payment' => Order::where('status', 'waiting_payment')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Verify transaction status with Pakasir API
     */
    private function verifyTransaction(string $orderId, int $amount): bool
    {
        try {
            $pakasirProject = config('pakasir.project');
            $pakasirApiKey = config('pakasir.api_key');

            $response = Http::get('https://app.pakasir.com/api/transactiondetail', [
                'project' => $pakasirProject,
                'amount' => $amount,
                'order_id' => $orderId,
                'api_key' => $pakasirApiKey,
            ]);

            if ($response->successful()) {
                $transaction = $response->json('transaction');
                return ($transaction['status'] ?? '') === 'completed';
            }
        } catch (\Exception $e) {
            Log::warning('Failed to verify transaction', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}

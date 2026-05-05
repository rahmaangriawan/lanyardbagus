<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index(): JsonResponse
    {
        $redirects = Redirect::orderBy('created_at', 'desc')->get();
        return response()->json($redirects);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'from_url'    => ['required', 'string', 'max:500'],
            'to_url'      => ['required', 'string', 'max:500'],
            'status_code' => ['nullable', 'integer', 'in:301,302,307,308'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $redirect = Redirect::create($data);

        return response()->json($redirect, 201);
    }

    public function update(Request $request, Redirect $redirect): JsonResponse
    {
        $data = $request->validate([
            'from_url'    => ['sometimes', 'string', 'max:500'],
            'to_url'      => ['sometimes', 'string', 'max:500'],
            'status_code' => ['nullable', 'integer', 'in:301,302,307,308'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $redirect->update($data);

        return response()->json($redirect);
    }

    public function destroy(Redirect $redirect): JsonResponse
    {
        $redirect->delete();

        return response()->json(['message' => 'Redirect deleted']);
    }
}

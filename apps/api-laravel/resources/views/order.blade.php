<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Lanyard - Lanyard Bagus</title>
    <meta name="description" content="Pesan lanyard custom berkualitas tinggi. Berbagai jenis dan ukuran tersedia.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }
        .container { max-width: 800px; margin: 0 auto; padding: 24px 16px; }
        .page-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .page-header p {
            color: #64748b;
            font-size: 15px;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: white;
        }
        .form-input:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        @media (max-width: 480px) {
            .form-row { grid-template-columns: 1fr; }
        }
        .item-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 10px;
            align-items: end;
            margin-bottom: 12px;
        }
        @media (max-width: 640px) {
            .item-row { grid-template-columns: 1fr 1fr; }
        }
        .btn-remove {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #fca5a5;
            background: #fef2f2;
            color: #dc2626;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }
        .btn-remove:hover { background: #fee2e2; }
        .btn-add {
            background: none;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s;
        }
        .btn-add:hover { border-color: #3b82f6; color: #3b82f6; }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
        }
        .payment-method {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s;
            font-size: 13px;
            font-weight: 500;
        }
        .payment-method:hover { border-color: #93c5fd; }
        .payment-method.active {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .payment-method .method-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }
        .summary-row.total {
            border-top: 1px solid #e5e7eb;
            margin-top: 8px;
            padding-top: 12px;
            font-weight: 600;
            font-size: 16px;
        }
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
        }
        .btn-submit:hover { background: #2563eb; }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Payment Result */
        .payment-result {
            display: none;
            text-align: center;
        }
        .payment-result.show { display: block; }
        .qr-container {
            background: white;
            border-radius: 16px;
            padding: 32px;
            border: 1px solid #e5e7eb;
            display: inline-block;
            margin: 16px 0;
        }
        .qr-container canvas { max-width: 250px; }
        .va-number {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            background: #f1f5f9;
            padding: 16px 24px;
            border-radius: 10px;
            display: inline-block;
            margin: 16px 0;
        }
        .timer {
            color: #dc2626;
            font-weight: 600;
            font-size: 18px;
            margin: 12px 0;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-badge.paid { background: #dcfce7; color: #16a34a; }
        .status-badge.waiting { background: #fef3c7; color: #d97706; }
        .status-badge.expired { background: #fee2e2; color: #dc2626; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 16px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Beranda
        </a>

        <!-- Order Form -->
        <div id="order-form-section">
            <div class="page-header">
                <h1>Pesan Lanyard Custom</h1>
                <p>Isi form di bawah untuk membuat pesanan lanyard custom Anda</p>
            </div>

            <form id="order-form">
                <!-- Customer Info -->
                <div class="card">
                    <div class="card-title">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Informasi Pemesan
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_name">Nama Lengkap *</label>
                            <input type="text" id="customer_name" class="form-input" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">Nomor WhatsApp *</label>
                            <input type="tel" id="customer_phone" class="form-input" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customer_email">Email (opsional)</label>
                        <input type="email" id="customer_email" class="form-input" placeholder="email@contoh.com">
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-title">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        Detail Pesanan
                    </div>
                    <div id="items-container"></div>
                    <button type="button" class="btn-add" onclick="addItem()">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Item
                    </button>
                </div>

                <!-- Notes -->
                <div class="card">
                    <div class="card-title">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Catatan Tambahan
                    </div>
                    <div class="form-group">
                        <textarea id="notes" class="form-input" rows="3" placeholder="Catatan khusus untuk pesanan (warna, desain, dll)"></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card">
                    <div class="card-title">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        Metode Pembayaran
                    </div>
                    <div class="payment-methods">
                        <div class="payment-method active" data-method="qris" onclick="selectPayment(this)">
                            <div class="method-icon">📱</div>
                            QRIS
                        </div>
                        <div class="payment-method" data-method="bni_va" onclick="selectPayment(this)">
                            <div class="method-icon">🏦</div>
                            BNI VA
                        </div>
                        <div class="payment-method" data-method="bri_va" onclick="selectPayment(this)">
                            <div class="method-icon">🏦</div>
                            BRI VA
                        </div>
                        <div class="payment-method" data-method="cimb_niaga_va" onclick="selectPayment(this)">
                            <div class="method-icon">🏦</div>
                            CIMB Niaga VA
                        </div>
                        <div class="payment-method" data-method="permata_va" onclick="selectPayment(this)">
                            <div class="method-icon">🏦</div>
                            Permata VA
                        </div>
                        <div class="payment-method" data-method="maybank_va" onclick="selectPayment(this)">
                            <div class="method-icon">🏦</div>
                            Maybank VA
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="card">
                    <div class="card-title">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                        Ringkasan Pesanan
                    </div>
                    <div id="order-summary">
                        <p style="color:#64748b; font-size:14px;">Pilih item untuk melihat ringkasan</p>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    Bayar Sekarang
                </button>
            </form>
        </div>

        <!-- Payment Result -->
        <div id="payment-result-section" class="payment-result">
            <div class="page-header">
                <h1>Menunggu Pembayaran</h1>
                <p id="result-subtitle">Silakan lakukan pembayaran sebelum waktu habis</p>
            </div>

            <div class="card">
                <div id="payment-info"></div>
                <div class="timer" id="countdown-timer"></div>
            </div>

            <div class="card">
                <div class="card-title">Detail Pesanan</div>
                <div id="result-order-detail"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        const API_URL = '{{ url("/") }}';
        let selectedPayment = 'qris';
        let itemIndex = 0;
        let allProducts = [];
        let preselectedProductId = null;

        // Load products from API on page load
        async function loadProducts() {
            try {
                const params = new URLSearchParams(window.location.search);
                preselectedProductId = params.get('product_id');

                // Fetch all active products grouped by category
                const res = await fetch(`${API_URL}/api/products`);
                if (res.ok) {
                    allProducts = await res.json();
                }
            } catch (e) {
                console.warn('Could not load products from API, using fallback');
            }

            // Add initial item row
            if (preselectedProductId) {
                const preProduct = allProducts.find(p => p.id == preselectedProductId);
                if (preProduct && (preProduct.variants?.length || preProduct.accessories?.length)) {
                    openVAModal(preProduct, (va) => addItem(preselectedProductId, va));
                } else {
                    addItem(preselectedProductId);
                }
            } else {
                addItem();
            }
        }

        function buildProductOptions(selectedId) {
            let html = '<option value="">Pilih produk</option>';
            let currentCategory = '';
            allProducts.forEach(p => {
                const catName = p.category?.name || 'Lainnya';
                if (catName !== currentCategory) {
                    if (currentCategory) html += '</optgroup>';
                    html += `<optgroup label="${catName}">`;
                    currentCategory = catName;
                }
                const selected = (p.id == selectedId) ? 'selected' : '';
                html += `<option value="${p.id}" data-price="${p.price}" data-min="${p.min_order}" ${selected}>${p.name} - Rp ${Number(p.price).toLocaleString('id-ID')}/pcs</option>`;
            });
            if (currentCategory) html += '</optgroup>';
            return html;
        }

        function addItem(productId, va) {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.className = 'item-row';
            row.dataset.index = itemIndex++;
            const idx = row.dataset.index;
            const vaStr = va ? JSON.stringify(va) : '';
            row.dataset.va = vaStr;

            const hasProducts = allProducts.length > 0;

            let vaDisplay = '';
            if (va) {
                if (va.variant) vaDisplay += `<span style="display:inline-block; font-size:11px; color:#1e293b; background:#f1f5f9; border-radius:6px; padding:2px 8px; margin-right:6px;">Varian: ${va.variant.name}</span>`;
                if (va.accessories && va.accessories.length) {
                    va.accessories.forEach(a => {
                        vaDisplay += `<span style="display:inline-block; font-size:11px; color:#1e293b; background:#f1f5f9; border-radius:6px; padding:2px 8px; margin-right:6px;">+ ${a.name}</span>`;
                    });
                }
            }

            row.innerHTML = `
                <div class="form-group" style="grid-column: span 2;">
                    <label>Produk *</label>
                    <select class="form-select item-product" data-idx="${idx}" required onchange="onProductChange(${idx})">
                        ${hasProducts ? buildProductOptions(productId) : '<option value="">Memuat produk...</option>'}
                    </select>
                    <div style="margin-top:4px;">${vaDisplay}</div>
                </div>
                <div class="form-group">
                    <label>Jumlah (pcs) *</label>
                    <input type="number" class="form-input item-qty" data-idx="${idx}" min="50" value="100" required oninput="updateSummary()">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn-remove" onclick="removeItem(this)" ${itemIndex <= 1 ? 'style="display:none"' : ''}>
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            lucide.createIcons();
            updateSummary();
        }

        function onProductChange(idx) {
            const select = document.querySelector(`.item-product[data-idx="${idx}"]`);
            const qtyInput = document.querySelector(`.item-qty[data-idx="${idx}"]`);
            if (!select) return;

            const option = select.options[select.selectedIndex];
            const minOrder = parseInt(option?.dataset?.min) || 50;
            if (qtyInput && parseInt(qtyInput.value) < minOrder) {
                qtyInput.value = minOrder;
                qtyInput.min = minOrder;
            }
            updateSummary();
        }

        function removeItem(btn) {
            btn.closest('.item-row').remove();
            updateSummary();
        }

        function getItems() {
            const rows = document.querySelectorAll('.item-row');
            const items = [];
            rows.forEach(row => {
                const select = row.querySelector('.item-product');
                const qtyInput = row.querySelector('.item-qty');
                const productId = select?.value;
                const qty = parseInt(qtyInput?.value) || 0;
                if (productId && qty > 0) {
                    const option = select.options[select.selectedIndex];
                    const basePrice = parseInt(option?.dataset?.price) || 0;
                    let name = option?.textContent?.split(' - ')[0] || select.value;
                    let price = basePrice;

                    let va = null;
                    try { va = JSON.parse(row.dataset.va || '{}') } catch(e) {}
                    if (va && va.variant) {
                        name += ' - ' + va.variant.name;
                        price += parseInt(va.variant.price_adjustment || 0);
                    }
                    if (va && va.accessories && va.accessories.length) {
                        va.accessories.forEach(a => {
                            name += ' + ' + a.name;
                            price += parseInt(a.price || 0);
                        });
                    }

                    items.push({ name, product_id: productId, qty, price, size: '' });
                }
            });
            return items;
        }

        function updateSummary() {
            const items = getItems();
            const summary = document.getElementById('order-summary');

            if (items.length === 0) {
                summary.innerHTML = '<p style="color:#64748b; font-size:14px;">Pilih produk untuk melihat ringkasan</p>';
                return;
            }

            let total = 0;
            let html = '';
            items.forEach(item => {
                const unitPrice = item.price;
                const subtotal = item.qty * item.price;
                total += subtotal;
                html += `<div class="summary-row">
                    <span>${item.name} × ${item.qty} pcs <span style="color:#64748b; font-size:12px;">(Rp ${unitPrice.toLocaleString('id-ID')}/pcs)</span></span>
                    <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>`;
            });
            html += `<div class="summary-row total">
                <span>Total</span>
                <span>Rp ${total.toLocaleString('id-ID')}</span>
            </div>`;
            summary.innerHTML = html;
        }

        function formatRupiah(num) {
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        // Form submission
        document.getElementById('order-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="w-5 h-5 animate-spin"></i> Memproses...';

            const items = getItems();
            if (items.length === 0) {
                alert('Pilih minimal 1 produk');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="shield-check" class="w-5 h-5"></i> Bayar Sekarang';
                lucide.createIcons();
                return;
            }

            const payload = {
                customer_name: document.getElementById('customer_name').value.trim(),
                customer_phone: document.getElementById('customer_phone').value.trim(),
                customer_email: document.getElementById('customer_email').value.trim() || null,
                items: items,
                payment_method: selectedPayment,
                notes: document.getElementById('notes').value.trim() || null,
            };

            try {
                const response = await fetch(`${API_URL}/api/orders`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (response.ok) {
                    showPaymentResult(data);
                } else {
                    alert(data.error || 'Gagal membuat pesanan. Silakan coba lagi.');
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi. Periksa internet Anda.');
            }

            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="shield-check" class="w-5 h-5"></i> Bayar Sekarang';
            lucide.createIcons();
        });

        function showPaymentResult(data) {
            document.getElementById('order-form-section').style.display = 'none';
            const resultSection = document.getElementById('payment-result-section');
            resultSection.classList.add('show');

            const payment = data.payment;
            const order = data.order;
            const paymentInfo = document.getElementById('payment-info');

            if (selectedPayment === 'qris') {
                paymentInfo.innerHTML = `
                    <p style="font-size:14px; color:#64748b; margin-bottom:12px;">Scan QR code di bawah untuk bayar</p>
                    <div class="qr-container">
                        <canvas id="qr-canvas"></canvas>
                    </div>
                    <p style="font-size:14px; margin-top:12px;"><strong>Total: ${formatRupiah(payment.total_payment)}</strong></p>
                    <p style="font-size:12px; color:#64748b;">Order ID: ${order.order_id}</p>
                `;
                setTimeout(() => {
                    QRCode.toCanvas(document.getElementById('qr-canvas'), payment.payment_number, {
                        width: 250,
                        margin: 2,
                    });
                }, 100);
            } else {
                paymentInfo.innerHTML = `
                    <p style="font-size:14px; color:#64748b; margin-bottom:12px;">Transfer ke Virtual Account berikut:</p>
                    <div class="va-number">${payment.payment_number}</div>
                    <p style="font-size:14px; margin-top:12px;"><strong>Total: ${formatRupiah(payment.total_payment)}</strong></p>
                    <p style="font-size:12px; color:#64748b;">Order ID: ${order.order_id}</p>
                `;
            }

            let detailHtml = '';
            order.items.forEach(item => {
                detailHtml += `<div class="summary-row">
                    <span>${item.name} × ${item.qty} pcs</span>
                    <span>${formatRupiah(item.qty * item.price)}</span>
                </div>`;
            });
            detailHtml += `<div class="summary-row"><span>Biaya Admin</span><span>${formatRupiah(payment.fee)}</span></div>`;
            detailHtml += `<div class="summary-row total"><span>Total Bayar</span><span>${formatRupiah(payment.total_payment)}</span></div>`;
            document.getElementById('result-order-detail').innerHTML = detailHtml;

            if (payment.expired_at) {
                startCountdown(new Date(payment.expired_at), order.order_id);
            }
        }

        function startCountdown(expiredAt, orderId) {
            const timerEl = document.getElementById('countdown-timer');
            const interval = setInterval(() => {
                const now = new Date();
                const diff = expiredAt - now;

                if (diff <= 0) {
                    clearInterval(interval);
                    timerEl.textContent = 'Waktu pembayaran habis';
                    return;
                }

                const hours = Math.floor(diff / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                timerEl.textContent = `Sisa waktu: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);

            const statusInterval = setInterval(async () => {
                try {
                    const res = await fetch(`${API_URL}/api/orders/${orderId}/status`);
                    const data = await res.json();
                    if (data.order && data.order.status === 'paid') {
                        clearInterval(interval);
                        clearInterval(statusInterval);
                        document.getElementById('countdown-timer').innerHTML = '<span class="status-badge paid">✅ Pembayaran Berhasil!</span>';
                        document.getElementById('result-subtitle').textContent = 'Terima kasih! Pembayaran Anda telah diterima.';
                    }
                } catch (e) {}
            }, 5000);
        }

        // ================= VARIANT & ACCESSORY MODAL =================
        let vaProduct = null;
        let vaCallback = null;

        function openVAModal(product, onConfirm) {
            vaProduct = product;
            vaCallback = onConfirm;
            const modal = document.getElementById('va-modal');
            modal.style.display = 'flex';
            document.getElementById('va-product-info').innerHTML = `<strong>${product.name}</strong> <span style="color:#64748b; font-size:13px;">- Rp ${Number(product.price).toLocaleString('id-ID')}/pcs</span>`;

            // Variants
            const varSec = document.getElementById('va-variants-section');
            if (product.variants && product.variants.length > 0) {
                let html = `<label style="font-size:13px; font-weight:600; display:block; margin-bottom:8px;">Pilih Varian</label>`;
                html += `<div style="display:flex; flex-wrap:wrap; gap:8px;">`;
                product.variants.forEach((v, i) => {
                    const label = v.price_adjustment > 0 ? `${v.name} (+Rp ${Number(v.price_adjustment).toLocaleString('id-ID')})` : v.name;
                    html += `<label style="cursor:pointer; display:flex; align-items:center; gap:6px; border:1.5px solid #e5e7eb; border-radius:10px; padding:8px 12px; font-size:13px; transition:all .15s;" class="va-variant-chip" data-idx="${i}" onclick="selectVariantChip(this)">
                        <input type="radio" name="va_variant" value="${i}" style="accent-color:#1e293b;">
                        <span>${label}</span>
                    </label>`;
                });
                html += `</div>`;
                varSec.innerHTML = html;
            } else {
                varSec.innerHTML = '';
            }

            // Accessories
            const accSec = document.getElementById('va-accessories-section');
            if (product.accessories && product.accessories.length > 0) {
                let html = `<label style="font-size:13px; font-weight:600; display:block; margin-bottom:8px; margin-top:4px;">Pilih Aksesoris (bisa lebih dari 1)</label>`;
                html += `<div style="display:flex; flex-wrap:wrap; gap:8px;">`;
                product.accessories.forEach((a, i) => {
                    const label = a.price > 0 ? `${a.name} (+Rp ${Number(a.price).toLocaleString('id-ID')})` : a.name;
                    html += `<label style="cursor:pointer; display:flex; align-items:center; gap:6px; border:1.5px solid #e5e7eb; border-radius:10px; padding:8px 12px; font-size:13px; transition:all .15s;" class="va-accessory-chip" data-idx="${i}" onclick="toggleAccessoryChip(this)">
                        <input type="checkbox" value="${i}" style="accent-color:#1e293b;">
                        <span>${label}</span>
                    </label>`;
                });
                html += `</div>`;
                accSec.innerHTML = html;
            } else {
                accSec.innerHTML = '';
            }
            lucide.createIcons();
        }

        function selectVariantChip(el) {
            document.querySelectorAll('.va-variant-chip').forEach(c => c.style.borderColor = '#e5e7eb');
            el.style.borderColor = '#1e293b';
            el.querySelector('input').checked = true;
        }

        function toggleAccessoryChip(el) {
            const cb = el.querySelector('input');
            cb.checked = !cb.checked;
            el.style.borderColor = cb.checked ? '#1e293b' : '#e5e7eb';
        }

        function closeVAModal() {
            document.getElementById('va-modal').style.display = 'none';
            vaProduct = null;
            vaCallback = null;
        }

        function confirmVASelection() {
            if (!vaProduct || !vaCallback) return;
            const selectedVariantIdx = document.querySelector('input[name="va_variant"]:checked');
            const selectedAccessoryIdxes = Array.from(document.querySelectorAll('#va-accessories-section input[type="checkbox"]:checked')).map(cb => parseInt(cb.value));
            const result = {
                variant: selectedVariantIdx ? vaProduct.variants[parseInt(selectedVariantIdx.value)] : null,
                accessories: selectedAccessoryIdxes.map(i => vaProduct.accessories[i])
            };
            closeVAModal();
            vaCallback(result);
        }

        // Init
        loadProducts();
        lucide.createIcons();
    </script>
    <!-- Variant & Accessory Modal -->
    <div id="va-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:100; align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; border-radius:16px; max-width:520px; width:100%; max-height:90vh; overflow-y:auto; box-shadow:0 20px 40px rgba(0,0,0,0.15);">
            <div style="padding:20px 24px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between;">
                <h3 style="font-size:16px; font-weight:700;">Pilih Varian & Aksesoris</h3>
                <button type="button" onclick="closeVAModal()" style="background:none; border:none; cursor:pointer; padding:4px;"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div style="padding:20px 24px;">
                <div id="va-product-info" style="margin-bottom:16px;"></div>
                <div id="va-variants-section" style="margin-bottom:16px;"></div>
                <div id="va-accessories-section" style="margin-bottom:16px;"></div>
            </div>
            <div style="padding:16px 24px 20px; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeVAModal()" style="padding:8px 16px; border:1px solid #e5e7eb; border-radius:8px; background:white; font-size:13px; cursor:pointer;">Batal</button>
                <button type="button" onclick="confirmVASelection()" style="padding:8px 16px; background:#1e293b; color:white; border:none; border-radius:8px; font-size:13px; cursor:pointer; font-weight:600;">Tambah ke Pesanan</button>
            </div>
        </div>
    </div>

</body>
</html>

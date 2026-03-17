<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
        }
        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 1rem;
            overflow: hidden;
        }
        .invoice-header {
            background: linear-gradient(135deg, #4361ee 0%, #3730a3 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .invoice-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        .invoice-content {
            padding: 2rem;
        }
        .invoice-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px dashed #e2e8f0;
        }
        .invoice-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #4361ee;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title i {
            font-size: 1.5rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        .info-item {
            margin-bottom: 0.75rem;
        }
        .info-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-weight: 600;
            color: #1e293b;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .order-details th {
            background: #f1f5f9;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #cbd5e1;
        }
        .order-details td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .order-details tr:last-child td {
            border-bottom: none;
        }
        .price-summary {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #cbd5e1;
        }
        .price-row:last-child {
            border-bottom: none;
        }
        .price-total {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: #4361ee;
            border-top: 2px solid #4361ee;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }
        .payment-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .paid { background: #d1fae5; color: #065f46; }
        .pending { background: #fef3c7; color: #92400e; }
        .failed { background: #fee2e2; color: #991b1b; }
        .footer {
            text-align: center;
            padding: 1.5rem;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.875rem;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            opacity: 0.03;
            pointer-events: none;
            z-index: 1000;
            color: #4361ee;
            font-weight: 700;
            white-space: nowrap;
        }
        @media print {
            body { background: white; }
            .invoice-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="watermark">LAUNDRYAPP</div>
    
    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>#{{ $order->order_number }}</p>
        </div>

        <!-- Content -->
        <div class="invoice-content">
            <!-- Order Info Section -->
            <div class="invoice-section">
                <div class="section-title">
                    <span>📋</span> Order Information
                </div>
                <div class="info-grid">
                    <div>
                        <div class="info-item">
                            <div class="info-label">Order Date</div>
                            <div class="info-value">{{ $order->created_at->format('F j, Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Order Time</div>
                            <div class="info-value">{{ $order->created_at->format('g:i A') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="status-badge">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="info-item">
                            <div class="info-label">Payment Method</div>
                            <div class="info-value">
                                @switch($order->payment_method)
                                    @case('mtn') 📱 MTN Money @break
                                    @case('airtel') 📱 Airtel Money @break
                                    @case('zamtel') 📱 Zamtel Money @break
                                    @case('wallet') 💰 Wallet @break
                                    @default {{ ucfirst($order->payment_method) }}
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Type</div>
                            <div class="info-value">{{ ucfirst($order->payment_type) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">
                                <span class="payment-status {{ $order->payment_status }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="invoice-section">
                <div class="section-title">
                    <span>👤</span> Customer Information
                </div>
                <div class="info-grid">
                    <div>
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div class="info-value">{{ $order->client->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $order->client->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $order->client->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Info -->
            <div class="invoice-section">
                <div class="section-title">
                    <span>📍</span> Location Details
                </div>
                <div class="info-grid">
                    <div>
                        <div class="info-item">
                            <div class="info-label">Pickup Location</div>
                            <div class="info-value">{{ $order->pickupLocation->name }}</div>
                            <div class="text-muted" style="font-size: 0.875rem;">{{ $order->pickupLocation->address }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Scheduled Pickup</div>
                            <div class="info-value">{{ $order->pickup_scheduled_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="info-item">
                            <div class="info-label">Delivery Location</div>
                            <div class="info-value">{{ $order->deliveryLocation->name }}</div>
                            <div class="text-muted" style="font-size: 0.875rem;">{{ $order->deliveryLocation->address }}</div>
                        </div>
                        @if($order->delivery_scheduled_at)
                        <div class="info-item">
                            <div class="info-label">Estimated Delivery</div>
                            <div class="info-value">{{ $order->delivery_scheduled_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="invoice-section">
                <div class="section-title">
                    <span>🧺</span> Order Details
                </div>
                
                <table class="order-details">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th style="text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>{{ $order->basket->name }}</strong></td>
                            <td>Laundry Basket ({{ $order->basket->capacity_kg }}kg)</td>
                            <td style="text-align: right;">K{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        
                        @if($order->special_items && count($order->special_items) > 0)
                            @foreach($order->special_items as $item)
                                @if(is_array($item))
                                <tr>
                                    <td><strong>Special Item</strong></td>
                                    <td>{{ $item['name'] ?? 'Special Item' }}</td>
                                    <td style="text-align: right;">+ K{{ number_format($item['surcharge'] ?? 0, 2) }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <!-- Price Summary -->
                <div class="price-summary">
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span>K{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    
                    @if($order->surcharges > 0)
                    <div class="price-row">
                        <span>Surcharges</span>
                        <span>+ K{{ number_format($order->surcharges, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->discount > 0)
                    <div class="price-row" style="color: #10b981;">
                        <span>Discount</span>
                        <span>- K{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    
                    <div class="price-total">
                        <span>TOTAL AMOUNT</span>
                        <span>K{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Special Instructions -->
            @if($order->special_instructions)
            <div class="invoice-section">
                <div class="section-title">
                    <span>📝</span> Special Instructions
                </div>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem;">
                    {{ $order->special_instructions }}
                </div>
            </div>
            @endif

            <!-- Transaction Reference -->
            @if($order->transaction_reference)
            <div style="text-align: center; margin-top: 1rem; padding: 1rem; background: #f1f5f9; border-radius: 0.5rem;">
                <small class="text-muted">Transaction Reference</small>
                <div><code>{{ $order->transaction_reference }}</code></div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing LaundryApp!</p>
            <p style="margin-top: 0.5rem;">For any queries, please contact support@laundryapp.com</p>
            <p style="margin-top: 0.5rem; font-size: 0.75rem;">This is a computer generated invoice. No signature required.</p>
        </div>
    </div>
</body>
</html>
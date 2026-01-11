<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $appointment->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #0084ff;
        }
        
        .clinic-info h1 {
            color: #0084ff;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .clinic-info p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-meta h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .invoice-meta p {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .detail-section h3 {
            color: #333;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-section p {
            color: #666;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .invoice-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        
        .invoice-table thead {
            background: #f8f9fa;
        }
        
        .invoice-table th {
            padding: 15px;
            text-align: left;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .invoice-table td {
            padding: 15px;
            color: #666;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .invoice-table .text-right {
            text-align: right;
        }
        
        .invoice-totals {
            margin-left: auto;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #0084ff;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #0084ff;
        }
        
        .invoice-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-unpaid {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="clinic-info">
                <h1>RMDC</h1>
                <p>Robles Moncayo Dental Clinic</p>
                <p>Professional Dental Services</p>
                <p>Email: contact@rmdc.com</p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $appointment->id }}</p>
                <p><strong>Date:</strong> {{ $appointment->created_at->format('M d, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ $appointment->payment_status ?? 'unpaid' }}">
                        {{ strtoupper($appointment->payment_status ?? 'UNPAID') }}
                    </span>
                </p>
            </div>
        </div>
        
        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-section">
                <h3>Bill To:</h3>
                <p>
                    <strong>{{ $appointment->user->name ?? 'N/A' }}</strong><br>
                    {{ $appointment->user->email ?? 'N/A' }}
                </p>
            </div>
            <div class="detail-section">
                <h3>Appointment Details:</h3>
                <p>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->start)->format('F d, Y') }}<br>
                    <strong>Time:</strong> {{ $appointment->time }}<br>
                    <strong>Duration:</strong> {{ $appointment->duration }}
                </p>
            </div>
        </div>
        
        <!-- Services Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $appointment->procedure }}</strong><br>
                        <small>{{ $appointment->title }}</small>
                    </td>
                    <td class="text-right">₱{{ number_format($amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="invoice-totals">
            @if($appointment->down_payment)
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₱{{ number_format($amount, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Down Payment:</span>
                <span>₱{{ number_format($appointment->down_payment, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Balance:</span>
                <span>₱{{ number_format($amount - $appointment->down_payment, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>Total Amount:</span>
                <span>₱{{ number_format($amount, 2) }}</span>
            </div>
        </div>
        
        <!-- Payment Information -->
        @if($appointment->payment_method || $appointment->payment_reference)
        <div class="invoice-details" style="margin-top: 30px;">
            <div class="detail-section">
                <h3>Payment Information:</h3>
                <p>
                    @if($appointment->payment_method)
                        <strong>Method:</strong> {{ strtoupper($appointment->payment_method) }}<br>
                    @endif
                    @if($appointment->payment_reference)
                        <strong>Reference:</strong> {{ $appointment->payment_reference }}<br>
                    @endif
                </p>
            </div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p>Thank you for choosing RMDC - Robles Moncayo Dental Clinic</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For questions about this invoice, please contact us at contact@rmdc.com</p>
        </div>
    </div>
</body>
</html>

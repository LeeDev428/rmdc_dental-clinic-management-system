<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #ffffff;
            color: #1e293b;
            font-size: 12px;
        }
        
        .invoice-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 0;
            border: 1px solid #e5e7eb;
        }
        
        /* Header Section */
        .invoice-header {
            padding: 20px 25px;
            border-bottom: 2px solid #2563eb;
            background: #f8fafc;
        }
        
        .invoice-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 4px;
        }
        
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
        }
        
        .invoice-date {
            color: #64748b;
            font-size: 11px;
        }
        
        .invoice-number {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            margin-left: 8px;
            background: #dcfce7;
            color: #166534;
        }
        
        /* Info Grid Section */
        .info-grid {
            display: table;
            width: 100%;
            padding: 15px 25px;
            background: #ffffff;
        }
        
        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        
        .info-box h3 {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }
        
        .info-box p {
            color: #475569;
            font-size: 11px;
            line-height: 1.5;
            margin-bottom: 2px;
        }
        
        .info-box strong {
            color: #0f172a;
            font-weight: 600;
        }
        
        /* Appointment Details Section */
        .details-section {
            padding: 15px 25px;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .details-table thead {
            background: #f1f5f9;
        }
        
        .details-table th {
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .details-table th:last-child {
            text-align: right;
        }
        
        .details-table td {
            padding: 12px;
            color: #475569;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .details-table td:last-child {
            text-align: right;
            font-weight: 700;
            color: #0f172a;
        }
        
        .procedure-name {
            font-weight: 600;
            color: #0f172a;
            font-size: 12px;
        }
        
        .procedure-type {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        
        /* Payment Information */
        .payment-section {
            padding: 0 25px 20px 25px;
        }
        
        .payment-grid {
            display: table;
            width: 100%;
        }
        
        .payment-box {
            display: table-cell;
            width: 55%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-right: 15px;
            vertical-align: top;
        }
        
        .payment-box h4 {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }
        
        .payment-row {
            margin-bottom: 5px;
            font-size: 10px;
        }
        
        .payment-label {
            color: #64748b;
            display: inline-block;
            width: 100px;
        }
        
        .payment-value {
            color: #0f172a;
            font-weight: 600;
        }
        
        /* Summary Section */
        .summary-box {
            display: table-cell;
            width: 45%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            vertical-align: top;
        }
        
        .summary-row {
            display: table;
            width: 100%;
            padding: 6px 0;
            font-size: 11px;
        }
        
        .summary-label {
            display: table-cell;
            color: #475569;
        }
        
        .summary-value {
            display: table-cell;
            text-align: right;
            color: #0f172a;
            font-weight: 600;
        }
        
        .summary-row.deduction .summary-value {
            color: #dc2626;
        }
        
        .summary-row.total {
            border-top: 2px solid #e2e8f0;
            margin-top: 5px;
            padding-top: 8px;
        }
        
        .summary-row.total .summary-label {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }
        
        .summary-row.total .summary-value {
            font-size: 15px;
            font-weight: 700;
            color: #2563eb;
        }
        
        /* Footer */
        .invoice-footer {
            padding: 12px 25px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .invoice-footer p {
            color: #64748b;
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="invoice-title">
                <h1>BILLING INVOICE</h1>
            </div>
            <div class="invoice-meta">
                <span class="invoice-date">Issued: {{ $appointment->created_at->format('F d, Y') }}</span>
                <span>
                    <span class="invoice-number">Invoice #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span class="status-badge">{{ ucfirst($appointment->payment_status ?? 'PAID') }}</span>
                </span>
            </div>
        </div>
        
        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-box clinic">
                <h3>Clinic Information</h3>
                <p><strong>RMDC - Robles Moncayo Dental Clinic</strong></p>
                <p>Unit F Medina Bldg, Niog Elementary School, Bacoor, Cavite</p>
                <p>Email: robles_moncayo@yahoo.com | Phone: (+63) 912-3456-789</p>
            </div>
            
            <div class="info-box patient">
                <h3>Patient Information</h3>
                <p><strong>{{ $appointment->user->name }}</strong></p>
                <p>{{ $appointment->user->email }}</p>
                <p>Attending Doctor: Admin User</p>
            </div>
        </div>
        
        <!-- Appointment Details Section -->
        <div class="details-section">
            <h2 class="section-title">Appointment Details</h2>
            
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Schedule & Duration</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="procedure-name">{{ $appointment->procedure }}</div>
                            <div class="procedure-type">Dental Procedure</div>
                        </td>
                        <td>
                            <div>Date: {{ \Carbon\Carbon::parse($appointment->start)->format('F d, Y') }}</div>
                            <div>Time: {{ \Carbon\Carbon::parse($appointment->start)->format('g:i A') }}</div>
                            <div>Duration: {{ $appointment->duration ?? 60 }} minutes</div>
                        </td>
                        <td>PHP {{ number_format($appointment->total_price ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Payment Information & Summary -->
        <div class="payment-section">
            <div class="payment-grid">
                <div class="payment-box">
                    <h4>Payment Information</h4>
                    <div class="payment-row">
                        <span class="payment-label">Payment Method</span>
                        <span class="payment-value">{{ strtoupper($appointment->payment_method ?? 'GCASH') }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Reference ID</span>
                        <span class="payment-value">{{ $appointment->payment_reference ?? 'N/A' }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Status</span>
                        <span class="payment-value">{{ strtoupper($appointment->payment_status ?? 'PAID') }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Booked On</span>
                        <span class="payment-value">{{ $appointment->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                </div>
                
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="summary-label">Total Amount</span>
                        <span class="summary-value">PHP {{ number_format($appointment->total_price ?? 0, 2) }}</span>
                    </div>
                    <div class="summary-row deduction">
                        <span class="summary-label">Down Payment (20%)</span>
                        <span class="summary-value">-PHP {{ number_format($appointment->down_payment ?? 0, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span class="summary-label">Balance Due</span>
                        <span class="summary-value">PHP {{ number_format(($appointment->total_price ?? 0) - ($appointment->down_payment ?? 0), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p>Thank you for choosing RMDC - Robles Moncayo Dental Clinic</p>
            <p>For questions regarding this invoice, please contact us at robles_moncayo@yahoo.com</p>
        </div>
    </div>
</body>
</html>

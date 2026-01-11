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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding: 40px;
            background: #f5f7fa;
            color: #1e293b;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Header Section */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 32px 40px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .invoice-title h1 {
            font-size: 28px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }
        
        .invoice-date {
            color: #64748b;
            font-size: 14px;
        }
        
        .invoice-number-section {
            text-align: right;
        }
        
        .invoice-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
        }
        
        .invoice-number {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            background: #dcfce7;
            color: #166534;
        }
        
        /* Info Grid Section */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 32px 40px;
            background: #f8fafc;
        }
        
        .info-box h3 {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        
        .info-box p {
            color: #475569;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 4px;
        }
        
        .info-box strong {
            color: #0f172a;
            font-weight: 600;
        }
        
        /* Appointment Details Section */
        .details-section {
            padding: 32px 40px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 20px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .details-table thead {
            background: #f1f5f9;
        }
        
        .details-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .details-table th:last-child {
            text-align: right;
        }
        
        .details-table td {
            padding: 16px;
            color: #475569;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .details-table td:last-child {
            text-align: right;
        }
        
        .procedure-name {
            font-weight: 500;
            color: #0f172a;
        }
        
        .procedure-type {
            font-size: 13px;
            color: #64748b;
        }
        
        /* Payment Information */
        .payment-section {
            padding: 0 40px 32px 40px;
        }
        
        .payment-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        
        .payment-box h4 {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .payment-label {
            color: #64748b;
        }
        
        .payment-value {
            color: #0f172a;
            font-weight: 500;
        }
        
        /* Summary Section */
        .summary-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-left: auto;
            width: 100%;
            max-width: 400px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        
        .summary-label {
            color: #475569;
        }
        
        .summary-value {
            color: #0f172a;
            font-weight: 500;
        }
        
        .summary-row.deduction .summary-value {
            color: #dc2626;
        }
        
        .summary-row.total {
            border-top: 2px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 16px;
        }
        
        .summary-row.total .summary-label {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
        }
        
        .summary-row.total .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #2563eb;
        }
        
        /* Footer */
        .invoice-footer {
            padding: 24px 40px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .invoice-footer p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
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
            <div class="invoice-title">
                <h1>Billing Invoice</h1>
                <p class="invoice-date">Issued: {{ $appointment->created_at->format('F d, Y') }}</p>
            </div>
            <div class="invoice-number-section">
                <p class="invoice-label">Invoice Number</p>
                <div class="invoice-number">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
                <span class="status-badge">{{ ucfirst($appointment->payment_status ?? 'Completed') }}</span>
            </div>
        </div>
        
        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-box clinic">
                <h3>Clinic Information</h3>
                <p><strong>RMDC - Robles Moncayo Dental Clinic</strong></p>
                <p>Unit F Medina Bldg, Niog Elementary School</p>
                <p>Bacoor, Cavite, Philippines</p>
                <p><br></p>
                <p>Email: robles_moncayo@yahoo.com</p>
                <p>Phone: (+63) 912-3456-789</p>
            </div>
            
            <div class="info-box patient">
                <h3>Patient Information</h3>
                <p><strong>Patient Name</strong></p>
                <p>{{ $appointment->user->name }}</p>
                <p><br></p>
                <p><strong>Email Address</strong></p>
                <p>{{ $appointment->user->email }}</p>
                <p><br></p>
                <p><strong>Attending Doctor</strong></p>
                <p>Admin User</p>
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
                            <div>Date: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</div>
                            <div>Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($appointment->duration ?? 60)->format('g:i A') }}</div>
                            <div>Duration: {{ $appointment->duration ?? '1.5 hours' }} minutes</div>
                        </td>
                        <td>₱{{ number_format($appointment->price, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Payment Information & Summary -->
        <div class="payment-section">
            <div class="payment-box">
                <h4>Payment Information</h4>
                <div class="payment-row">
                    <span class="payment-label">Payment Method</span>
                    <span class="payment-value">{{ strtoupper($appointment->payment_method ?? 'GCASH') }}</span>
                </div>
                <div class="payment-row">
                    <span class="payment-label">Reference ID</span>
                    <span class="payment-value">{{ $appointment->payment_reference ?? 'pay_7z?Qw?qPADVNwV4F1jLQsb?q' }}</span>
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
                    <span class="summary-value">₱{{ number_format($appointment->price, 2) }}</span>
                </div>
                <div class="summary-row deduction">
                    <span class="summary-label">Down Payment (20%)</span>
                    <span class="summary-value">- ₱{{ number_format($appointment->down_payment ?? ($appointment->price * 0.2), 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span class="summary-label">Balance Due</span>
                    <span class="summary-value">₱{{ number_format($appointment->price - ($appointment->down_payment ?? ($appointment->price * 0.2)), 2) }}</span>
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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .appointment-details {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        .appointment-details h3 {
            margin-top: 0;
            color: #4CAF50;
            font-size: 18px;
        }
        .detail-row {
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 140px;
        }
        .value {
            color: #333;
        }
        .clinic-info {
            background-color: #e8f5e9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .clinic-info h4 {
            margin-top: 0;
            color: #2e7d32;
        }
        .important-notes {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .important-notes h4 {
            margin-top: 0;
            color: #856404;
        }
        .important-notes ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .important-notes li {
            margin: 8px 0;
            color: #856404;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>✓ Appointment Confirmed!</h1>
            <p>RMDC - Robles Moncayo Dental Clinic</p>
        </div>
        
        <div class="content">
            <p class="greeting">Dear <strong>{{ $appointment->user->name }}</strong>,</p>
            
            <p>Thank you for choosing <strong>Robles Moncayo Dental Clinic (RMDC)</strong>. Your appointment has been successfully booked.</p>
            
            <div class="appointment-details">
                <h3>📋 Your Appointment Details</h3>
                <div class="detail-row">
                    <span class="label">Appointment ID:</span>
                    <span class="value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Procedure:</span>
                    <span class="value">{{ $appointment->procedure }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Date:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($appointment->start)->format('l, F d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Time:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end)->format('h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Duration:</span>
                    <span class="value">{{ $appointment->duration }} minutes</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="status-badge status-pending">{{ ucfirst($appointment->status) }} - Awaiting Admin Approval</span>
                </div>
            </div>

            <div class="appointment-details">
                <h3>💳 Payment Arrangement</h3>
                <div class="detail-row">
                    <span class="label">Total Amount:</span>
                    <span class="value"><strong>₱{{ number_format($appointment->total_price, 2) }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="label">Payment Method:</span>
                    <span class="value"><strong>Physical at Clinic</strong></span>
                </div>
                <div class="detail-row">
                    <span class="label">Amount Due at Visit:</span>
                    <span class="value" style="color: #ff6b6b;"><strong>₱{{ number_format($appointment->total_price, 2) }}</strong></span>
                </div>
            </div>

            <div class="clinic-info">
                <h4>📍 Clinic Information</h4>
                <p><strong>RMDC - Robles Moncayo Dental Clinic</strong></p>
                <p>Unit F Medina Bldg, in front gate of Niog Elementary School<br>
                Bacoor, Cavite, Philippines</p>
                <p><strong>Email:</strong> robles_moncayo@yahoo.com<br>
                <strong>Phone:</strong> (+63) 912-3456-789</p>
            </div>

            <div class="important-notes">
                <h4>⚠️ Important Reminders</h4>
                <ul>
                    <li><strong>Arrive 10-15 minutes early</strong> to complete any necessary paperwork</li>
                    <li><strong>Bring a valid ID</strong> for verification purposes</li>
                    <li><strong>Wear comfortable clothing</strong> and avoid heavy meals before your appointment</li>
                    <li><strong>Your appointment is pending approval.</strong> You will receive a confirmation once the clinic admin reviews your booking</li>
                    <li>You will receive a <strong>reminder email 4 hours before</strong> your scheduled appointment</li>
                    <li><strong>Full payment</strong> is due at the clinic on the day of your appointment</li>
                    <li><strong>Need to reschedule?</strong> Please contact us at least 24 hours in advance</li>
                </ul>
            </div>
            
            <p>If you have any questions or concerns about your appointment, please don't hesitate to contact us. We look forward to providing you with excellent dental care!</p>
            
            <p style="margin-top: 30px;">Warm regards,<br>
            <strong>Robles Moncayo Dental Clinic Team</strong></p>
        </div>
        
        <div class="footer">
            <p><strong>This is an automated confirmation email</strong></p>
            <p>RMDC - Robles Moncayo Dental Clinic</p>
            <p>&copy; {{ date('Y') }} RMDC. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

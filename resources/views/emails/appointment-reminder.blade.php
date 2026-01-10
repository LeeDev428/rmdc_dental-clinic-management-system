<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Reminder</title>
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
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
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
        .reminder-box {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border-left: 4px solid #FF9800;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .reminder-box h3 {
            margin: 0;
            color: #856404;
            font-size: 24px;
        }
        .reminder-box p {
            margin: 10px 0 0 0;
            color: #856404;
            font-size: 16px;
        }
        .appointment-details {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #FF9800;
            border-radius: 4px;
        }
        .appointment-details h3 {
            margin-top: 0;
            color: #FF9800;
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
            min-width: 120px;
        }
        .value {
            color: #333;
        }
        .time-highlight {
            background-color: #fff3cd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
            border: 2px solid #FF9800;
        }
        .time-highlight h4 {
            margin: 0;
            color: #FF9800;
            font-size: 20px;
        }
        .time-highlight p {
            margin: 10px 0 0 0;
            color: #856404;
            font-size: 24px;
            font-weight: bold;
        }
        .checklist {
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .checklist h4 {
            margin-top: 0;
            color: #2e7d32;
        }
        .checklist ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .checklist li {
            margin: 8px 0;
            color: #2e7d32;
        }
        .contact-info {
            background-color: #e3f2fd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            border-left: 4px solid #2196F3;
        }
        .contact-info h4 {
            margin-top: 0;
            color: #1565c0;
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
            <h1>⏰ Appointment Reminder</h1>
            <p>RMDC - Robles Moncayo Dental Clinic</p>
        </div>
        
        <div class="content">
            <p class="greeting">Dear <strong>{{ $appointment->user->name }}</strong>,</p>
            
            <div class="reminder-box">
                <h3>⚠️ Your appointment is in 4 hours!</h3>
                <p>This is a friendly reminder about your upcoming dental appointment</p>
            </div>
            
            <div class="time-highlight">
                <h4>📅 Appointment Time</h4>
                <p>{{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }}</p>
                <p style="font-size: 16px; font-weight: normal; margin-top: 5px;">
                    {{ \Carbon\Carbon::parse($appointment->start)->format('l, F d, Y') }}
                </p>
            </div>
            
            <div class="appointment-details">
                <h3>📋 Appointment Summary</h3>
                <div class="detail-row">
                    <span class="label">Appointment ID:</span>
                    <span class="value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Procedure:</span>
                    <span class="value"><strong>{{ $appointment->procedure }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="label">Time Slot:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end)->format('h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Duration:</span>
                    <span class="value">{{ $appointment->duration }} minutes</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="value" style="color: #4CAF50; font-weight: bold;">{{ ucfirst($appointment->status) }}</span>
                </div>
            </div>

            @if($appointment->total_price && $appointment->down_payment)
            <div class="appointment-details">
                <h3>💰 Payment Status</h3>
                <div class="detail-row">
                    <span class="label">Down Payment:</span>
                    <span class="value" style="color: #4CAF50;">₱{{ number_format($appointment->down_payment, 2) }} (Paid)</span>
                </div>
                <div class="detail-row">
                    <span class="label">Balance Due:</span>
                    <span class="value" style="color: #ff6b6b; font-weight: bold;">₱{{ number_format($appointment->total_price - $appointment->down_payment, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="value" style="font-size: 12px; color: #666;">Please bring the balance payment to your appointment</span>
                </div>
            </div>
            @endif

            <div class="checklist">
                <h4>✓ Pre-Appointment Checklist</h4>
                <ul>
                    <li><strong>Arrive 10-15 minutes early</strong> - This allows time for check-in and paperwork</li>
                    <li><strong>Bring your valid ID</strong> - Required for verification</li>
                    <li><strong>Bring payment for balance</strong> - Cash or accepted payment methods</li>
                    <li><strong>Avoid heavy meals</strong> - Eat light before your dental procedure</li>
                    <li><strong>Inform us of any medications</strong> - Important for your safety</li>
                    <li><strong>List any allergies</strong> - Let us know if you have any medical conditions</li>
                </ul>
            </div>

            <div class="contact-info">
                <h4>📍 Clinic Location & Contact</h4>
                <p><strong>RMDC - Robles Moncayo Dental Clinic</strong></p>
                <p>Unit F Medina Bldg, in front gate of Niog Elementary School<br>
                Bacoor, Cavite, Philippines</p>
                <p style="margin-top: 10px;">
                    <strong>📧 Email:</strong> robles_moncayo@yahoo.com<br>
                    <strong>📞 Phone:</strong> (+63) 912-3456-789
                </p>
                <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #90caf9; font-size: 13px;">
                    <strong>⚠️ Need to cancel or reschedule?</strong><br>
                    Please contact us immediately. Late cancellations may affect your booking privileges.
                </p>
            </div>
            
            <p style="margin-top: 30px; text-align: center; font-size: 16px;">
                We look forward to seeing you soon and providing you with excellent dental care!
            </p>
            
            <p style="margin-top: 20px; text-align: center;">
                <strong>Robles Moncayo Dental Clinic Team</strong>
            </p>
        </div>
        
        <div class="footer">
            <p><strong>This is an automated reminder email</strong></p>
            <p>RMDC - Robles Moncayo Dental Clinic</p>
            <p>&copy; {{ date('Y') }} RMDC. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Status Update</title>
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
            background: linear-gradient(135deg, {{ $action === 'accepted' ? '#4CAF50 0%, #45a049 100%' : '#f44336 0%, #d32f2f 100%' }});
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
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            {{ $action === 'accepted' ? 'background-color: #4CAF50; color: white;' : 'background-color: #f44336; color: white;' }}
        }
        .message-box {
            background-color: {{ $action === 'accepted' ? '#e8f5e9' : '#ffebee' }};
            border-left: 4px solid {{ $action === 'accepted' ? '#4CAF50' : '#f44336' }};
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .appointment-details {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid {{ $action === 'accepted' ? '#4CAF50' : '#f44336' }};
            border-radius: 4px;
        }
        .appointment-details h3 {
            margin-top: 0;
            color: {{ $action === 'accepted' ? '#4CAF50' : '#f44336' }};
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
        .next-steps {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .next-steps h4 {
            margin-top: 0;
            color: #1565c0;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
            color: #1565c0;
        }
        .contact-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            @if($action === 'accepted')
                <h1>✓ Appointment Approved!</h1>
            @else
                <h1>Appointment Update</h1>
            @endif
            <p>RMDC - Robles Moncayo Dental Clinic</p>
        </div>
        
        <div class="content">
            <p style="font-size: 18px;">Dear <strong>{{ $appointment->user->name }}</strong>,</p>
            
            <div style="text-align: center;">
                <div class="status-badge">
                    @if($action === 'accepted')
                        Approved
                    @else
                        {{ ucfirst($action) }}
                    @endif
                </div>
            </div>
            
            <div class="message-box">
                @if($action === 'accepted')
                    <p style="margin: 0; font-size: 16px;">
                        <strong>Great news!</strong> Your appointment request has been reviewed and approved by our clinic administrator. 
                        Your booking is now confirmed!
                    </p>
                @else
                    <p style="margin: 0; font-size: 16px;">
                        We regret to inform you that your appointment request has been <strong>{{ $action }}</strong>. 
                        This may be due to scheduling conflicts or other reasons.
                    </p>
                @endif
            </div>
            
            <div class="appointment-details">
                <h3>📋 Appointment Information</h3>
                <div class="detail-row">
                    <span class="label">Appointment ID:</span>
                    <span class="value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Procedure:</span>
                    <span class="value"><strong>{{ $appointment->procedure }}</strong></span>
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
                    <span class="label">Status:</span>
                    <span class="value" style="color: {{ $action === 'accepted' ? '#4CAF50' : '#f44336' }}; font-weight: bold;">{{ ucfirst($appointment->status) }}</span>
                </div>
            </div>

            @if($action === 'accepted')
                <div class="next-steps">
                    <h4>✓ What's Next?</h4>
                    <ul>
                        <li><strong>Mark your calendar</strong> - Your appointment is confirmed for {{ \Carbon\Carbon::parse($appointment->start)->format('F d, Y') }} at {{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }}</li>
                        <li><strong>Reminder notification</strong> - You'll receive an email reminder 4 hours before your appointment</li>
                        <li><strong>Arrive early</strong> - Please come 10-15 minutes before your scheduled time</li>
                        <li><strong>Bring valid ID</strong> - Required for verification</li>
                        @if($appointment->total_price && $appointment->down_payment)
                            <li><strong>Balance payment</strong> - ₱{{ number_format($appointment->total_price - $appointment->down_payment, 2) }} due at the clinic</li>
                        @endif
                    </ul>
                </div>
            @else
                <div class="next-steps">
                    <h4>What Can You Do?</h4>
                    <ul>
                        <li><strong>Book a new appointment</strong> - Select a different date or time that may be available</li>
                        <li><strong>Contact us</strong> - Call or email us to discuss alternative arrangements</li>
                        <li><strong>Your payment</strong> - If you paid a down payment, please contact us regarding refund procedures</li>
                    </ul>
                </div>
            @endif

            <div class="contact-info">
                <p style="margin: 0 0 10px 0;"><strong>📍 RMDC - Robles Moncayo Dental Clinic</strong></p>
                <p style="margin: 5px 0;">Unit F Medina Bldg, Niog Elementary School, Bacoor, Cavite</p>
                <p style="margin: 5px 0;"><strong>📧</strong> robles_moncayo@yahoo.com | <strong>📞</strong> (+63) 912-3456-789</p>
            </div>
            
            @if($action === 'accepted')
                <p style="margin-top: 30px; text-align: center; font-size: 16px;">
                    We look forward to seeing you soon and providing you with excellent dental care!
                </p>
            @else
                <p style="margin-top: 30px; text-align: center; font-size: 16px;">
                    We apologize for any inconvenience. Please don't hesitate to contact us if you have any questions.
                </p>
            @endif
            
            <p style="margin-top: 20px; text-align: center;">
                <strong>Robles Moncayo Dental Clinic Team</strong>
            </p>
        </div>
        
        <div class="footer">
            <p><strong>This is an automated notification email</strong></p>
            <p>RMDC - Robles Moncayo Dental Clinic</p>
            <p>&copy; {{ date('Y') }} RMDC. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

@extends('layouts.admin')

@section('title', 'Security Settings')

@section('content')
<style>
    .security-container {
        padding: 24px;
    }
    
    .security-header {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .security-card {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .security-card h3 {
        margin-top: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .security-item {
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
    }
    
    .security-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .security-item h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .security-item p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.4s;
        border-radius: 24px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #10b981;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
</style>

<div class="security-container">
    <div class="security-header">
        <h2><i class="fas fa-shield-alt"></i> Security Settings</h2>
        <p style="margin: 8px 0 0 0; color: #6b7280;">Manage security features and access controls</p>
    </div>

    <!-- Session Security -->
    <div class="security-card">
        <h3><i class="fas fa-clock"></i> Session Security</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Session Timeout</h4>
                    <p>Current: 120 minutes (configured in config/session.php)</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Active
                </span>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Secure Cookies</h4>
                    <p>HTTPS only cookies for enhanced security</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Enabled
                </span>
            </div>
        </div>
    </div>

    <!-- Rate Limiting -->
    <div class="security-card">
        <h3><i class="fas fa-tachometer-alt"></i> Rate Limiting</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>API Rate Limiting</h4>
                    <p>10 requests per minute per IP address</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Active
                </span>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Login Attempts</h4>
                    <p>Laravel Breeze default throttling (5 attempts per minute)</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Protected
                </span>
            </div>
        </div>
    </div>

    <!-- Database Security -->
    <div class="security-card">
        <h3><i class="fas fa-database"></i> Database Security</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Automatic Backups</h4>
                    <p>Database backups with 30-day retention</p>
                </div>
                <a href="{{ route('admin.database.backup') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Configure
                </a>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>SQL Injection Protection</h4>
                    <p>Laravel Eloquent ORM provides automatic protection</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Protected
                </span>
            </div>
        </div>
    </div>

    <!-- Activity Monitoring -->
    <div class="security-card">
        <h3><i class="fas fa-eye"></i> Activity Monitoring</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Activity Logs</h4>
                    <p>Track all user actions and system events</p>
                </div>
                <a href="{{ route('admin.activity.logs') }}" class="btn btn-primary">
                    <i class="fas fa-history"></i> View Logs
                </a>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>IP Tracking</h4>
                    <p>Log IP addresses for all user activities</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Enabled
                </span>
            </div>
        </div>
    </div>

    <!-- CSRF Protection -->
    <div class="security-card">
        <h3><i class="fas fa-lock"></i> Request Security</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>CSRF Protection</h4>
                    <p>Laravel CSRF tokens protect against cross-site request forgery</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Enabled
                </span>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>XSS Protection</h4>
                    <p>Blade templating engine escapes output automatically</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Protected
                </span>
            </div>
        </div>
    </div>

    <!-- Password Policy -->
    <div class="security-card">
        <h3><i class="fas fa-key"></i> Password Policy</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Password Requirements</h4>
                    <p>Minimum 8 characters (Laravel default validation)</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Enforced
                </span>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Password Hashing</h4>
                    <p>Bcrypt encryption for secure password storage</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Active
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

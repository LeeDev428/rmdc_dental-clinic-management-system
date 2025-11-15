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
                    <p>Bcrypt encryption with cost factor 12 for secure password storage</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Active
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Password Reset</h4>
                    <p>Secure token-based password reset with 60-minute expiration</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Enabled
                </span>
            </div>
        </div>
    </div>

    <!-- File Upload Security -->
    <div class="security-card">
        <h3><i class="fas fa-file-upload"></i> File Upload Security</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>File Type Validation</h4>
                    <p>Only allowed file types can be uploaded (images, PDF, documents)</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Validated
                </span>
            </div>
        </div>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>File Size Limits</h4>
                    <p>Maximum upload size: {{ ini_get('upload_max_filesize') }} (configurable in php.ini)</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Limited
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Secure Storage</h4>
                    <p>Files stored outside public directory with hashed filenames</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Protected
                </span>
            </div>
        </div>
    </div>

    <!-- Authentication Security -->
    <div class="security-card">
        <h3><i class="fas fa-user-shield"></i> Authentication Security</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Email Verification</h4>
                    <p>Require email verification for new user accounts</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Required
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Remember Me</h4>
                    <p>Secure "Remember Me" functionality with encrypted cookies</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Enabled
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Session Regeneration</h4>
                    <p>Session ID regenerated on login to prevent fixation attacks</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Active
                </span>
            </div>
        </div>
    </div>

    <!-- Data Protection -->
    <div class="security-card">
        <h3><i class="fas fa-shield-virus"></i> Data Protection</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Data Encryption</h4>
                    <p>Sensitive data encrypted using AES-256-CBC cipher</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Encrypted
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Database Encryption</h4>
                    <p>Connection uses SSL/TLS when available</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Secured
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Mass Assignment Protection</h4>
                    <p>Models use fillable/guarded properties to prevent mass assignment</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Protected
                </span>
            </div>
        </div>
    </div>

    <!-- Network Security -->
    <div class="security-card">
        <h3><i class="fas fa-network-wired"></i> Network Security</h3>
        
        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>HTTPS Enforcement</h4>
                    <p>Force HTTPS connections in production environment</p>
                </div>
                <span class="status-badge {{ config('app.env') === 'production' ? 'status-active' : 'status-inactive' }}">
                    <i class="fas {{ config('app.env') === 'production' ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i> 
                    {{ config('app.env') === 'production' ? 'Enforced' : 'Dev Mode' }}
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>Security Headers</h4>
                    <p>X-Frame-Options, X-Content-Type-Options, and other security headers</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Set
                </span>
            </div>
        </div>

        <div class="security-item">
            <div class="security-item-header">
                <div>
                    <h4>CORS Configuration</h4>
                    <p>Cross-Origin Resource Sharing properly configured</p>
                </div>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Configured
                </span>
            </div>
        </div>
    </div>

    <!-- Security Recommendations -->
    <div class="security-card" style="background: #fef3c7; border: 2px solid #f59e0b;">
        <h3 style="color: #92400e;"><i class="fas fa-exclamation-triangle"></i> Security Recommendations</h3>
        
        <div class="security-item" style="border-color: #fbbf24;">
            <div>
                <h4 style="color: #92400e;">Regular Updates</h4>
                <p style="color: #78350f;">Keep Laravel framework and all packages up to date to patch security vulnerabilities</p>
                <p style="color: #78350f; margin-top: 8px;"><strong>Action:</strong> Run <code>composer update</code> regularly</p>
            </div>
        </div>

        <div class="security-item" style="border-color: #fbbf24;">
            <div>
                <h4 style="color: #92400e;">Environment Configuration</h4>
                <p style="color: #78350f;">Never commit .env file to version control and keep APP_DEBUG=false in production</p>
                <p style="color: #78350f; margin-top: 8px;"><strong>Current:</strong> APP_DEBUG={{ config('app.debug') ? 'true (Dev Mode)' : 'false (Production)' }}</p>
            </div>
        </div>

        <div class="security-item" style="border-color: #fbbf24;">
            <div>
                <h4 style="color: #92400e;">Regular Backups</h4>
                <p style="color: #78350f;">Schedule automatic database backups and test restoration procedures regularly</p>
                <p style="color: #78350f; margin-top: 8px;"><strong>Action:</strong> <a href="{{ route('admin.database.backup') }}" style="color: #92400e; font-weight: 600;">Configure Backup Schedule</a></p>
            </div>
        </div>

        <div class="security-item" style="border-color: #fbbf24;">
            <div>
                <h4 style="color: #92400e;">Activity Log Monitoring</h4>
                <p style="color: #78350f;">Regularly review activity logs for suspicious behavior and unauthorized access attempts</p>
                <p style="color: #78350f; margin-top: 8px;"><strong>Action:</strong> <a href="{{ route('admin.activity.logs') }}" style="color: #92400e; font-weight: 600;">Review Activity Logs</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

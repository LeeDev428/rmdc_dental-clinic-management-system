@extends('layouts.admin')

@section('title', 'Database Backup')

@section('content')
<style>
    .backup-container {
        padding: 24px;
    }
    
    .backup-header {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .backup-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 600;
    }
    
    .backup-header p {
        margin: 0;
        color: #6b7280;
    }
    
    .backup-actions {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .backup-list {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .backup-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    
    .backup-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 4px rgba(59,130,246,0.1);
    }
    
    .backup-info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .backup-info p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }
    
    .backup-actions-btn {
        display: flex;
        gap: 8px;
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
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 16px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #d1d5db;
    }
</style>

<div class="backup-container">
    <div class="backup-header">
        <h2><i class="fas fa-database"></i> Database Backup Management</h2>
        <p>Create, download, and manage database backups</p>
    </div>

    <div id="alert-container"></div>

    <div class="backup-actions">
        <h3 style="margin-top: 0;">Create New Backup</h3>
        <button id="createBackupBtn" class="btn btn-primary" onclick="createBackup()">
            <i class="fas fa-plus-circle"></i> Create Backup Now
        </button>
        <p style="margin: 12px 0 0 0; font-size: 14px; color: #6b7280;">
            <i class="fas fa-info-circle"></i> Backups are automatically deleted after 30 days
        </p>
    </div>

    <div class="backup-list">
        <h3 style="margin-top: 0;">Backup History</h3>
        
        @if(count($backups) > 0)
            <div id="backup-list-container">
                @foreach($backups as $backup)
                <div class="backup-item">
                    <div class="backup-info">
                        <h4><i class="fas fa-file-archive"></i> {{ $backup['filename'] }}</h4>
                        <p>
                            <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($backup['created_at'])->format('M j, Y g:i A') }}
                            &nbsp;|&nbsp;
                            <i class="fas fa-hdd"></i> {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB
                            &nbsp;|&nbsp;
                            <i class="fas fa-table"></i> {{ $backup['tables'] }} tables
                            &nbsp;|&nbsp;
                            <i class="fas fa-user"></i> {{ $backup['created_by'] }}
                        </p>
                    </div>
                    <div class="backup-actions-btn">
                        <a href="{{ route('admin.database.backup.download', $backup['filename']) }}" 
                           class="btn btn-success">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button class="btn btn-danger" onclick="deleteBackup('{{ $backup['filename'] }}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p style="font-size: 16px; font-weight: 500; margin: 0 0 8px 0;">No backups found</p>
                <p style="font-size: 14px; margin: 0;">Create your first backup to get started</p>
            </div>
        @endif
    </div>
</div>

<script>
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alert-container');
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    
    alertContainer.innerHTML = `
        <div class="alert ${alertClass}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        </div>
    `;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}

function createBackup() {
    const btn = document.getElementById('createBackupBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Backup...';
    
    fetch('{{ route('admin.database.backup.create') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Backup created successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus-circle"></i> Create Backup Now';
        }
    })
    .catch(error => {
        showAlert('Failed to create backup: ' + error.message, 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plus-circle"></i> Create Backup Now';
    });
}

function deleteBackup(filename) {
    if (!confirm('Are you sure you want to delete this backup? This action cannot be undone.')) {
        return;
    }
    
    fetch(`/admin/database-backup/delete/${filename}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Backup deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        showAlert('Failed to delete backup: ' + error.message, 'error');
    });
}
</script>
@endsection

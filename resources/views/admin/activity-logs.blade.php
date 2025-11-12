@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<style>
    .logs-container {
        padding: 24px;
    }
    
    .logs-header {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .logs-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 600;
    }
    
    .filters-section {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        margin-bottom: 8px;
        font-weight: 500;
        font-size: 14px;
    }
    
    .form-control {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .logs-table-container {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow-x: auto;
    }
    
    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .logs-table th {
        background: #f9fafb;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .logs-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }
    
    .logs-table tr:hover {
        background: #f9fafb;
    }
    
    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-login { background: #dbeafe; color: #1e40af; }
    .badge-logout { background: #fee2e2; color: #991b1b; }
    .badge-create { background: #d1fae5; color: #065f46; }
    .badge-update { background: #fef3c7; color: #92400e; }
    .badge-delete { background: #fecaca; color: #991b1b; }
    .badge-default { background: #e5e7eb; color: #374151; }
    
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
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 16px;
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

<div class="logs-container">
    <div class="logs-header">
        <h2><i class="fas fa-history"></i> Activity Logs</h2>
        <p style="margin: 0; color: #6b7280;">Monitor all user activities and system events</p>
    </div>

    <div class="filters-section">
        <h3 style="margin-top: 0;">Filters</h3>
        <form method="GET" action="{{ route('admin.activity.logs') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="form-group">
                    <label>Activity Type</label>
                    <select name="type" class="form-control">
                        <option value="all">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.activity.logs') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
                <a href="{{ route('admin.activity.logs.export') }}" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Export CSV
                </a>
                <button type="button" class="btn btn-danger" onclick="clearOldLogs()">
                    <i class="fas fa-trash"></i> Clear Old Logs (90+ days)
                </button>
            </div>
        </form>
    </div>

    <div class="logs-table-container">
        <h3 style="margin-top: 0;">Activity History</h3>
        
        @if($logs->count() > 0)
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M j, Y g:i A') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge badge-{{ $log->type }}">
                                {{ ucfirst($log->type) }}
                            </span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 24px;">
                {{ $logs->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p style="font-size: 16px; font-weight: 500; margin: 0 0 8px 0;">No activity logs found</p>
                <p style="font-size: 14px; margin: 0;">Activity logs will appear here as users interact with the system</p>
            </div>
        @endif
    </div>
</div>

<script>
function clearOldLogs() {
    if (!confirm('This will delete all logs older than 90 days. Continue?')) {
        return;
    }
    
    fetch('{{ route('admin.activity.logs.clear') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ days: 90 })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        alert('Failed to clear logs: ' + error.message);
    });
}
</script>
@endsection

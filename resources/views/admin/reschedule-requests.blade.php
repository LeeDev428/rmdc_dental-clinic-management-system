@extends('layouts.admin')

@section('title', 'Reschedule Requests')

@section('content')
<style>
    .page-container {
        padding: 24px;
    }
    
    .page-header {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .page-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    .info-banner {
        background: #dbeafe;
        border-left: 4px solid #3b82f6;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
    }
    
    .info-banner p {
        margin: 0;
        font-size: 14px;
        color: #1e40af;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid #e5e7eb;
    }
    
    .stat-card.pending { border-left-color: #f59e0b; }
    .stat-card.approved { border-left-color: #10b981; }
    .stat-card.weekly { border-left-color: #3b82f6; }
    .stat-card.late { border-left-color: #ef4444; }
    
    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .content-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .content-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .content-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background: #f9fafb;
        padding: 12px 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .data-table td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }
    
    .data-table tr:hover {
        background: #f9fafb;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state p {
        margin: 8px 0 0 0;
        font-size: 14px;
    }
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>Reschedule Requests</h2>
        <p>Manage patient appointment reschedule requests</p>
    </div>

    <!-- Policy Info Banner -->
    <div class="info-banner">
        <p><strong>Policy:</strong> Reschedules must be made at least 2 days (48 hours) before appointment. Same-day reschedules not permitted. Only appointments not in current period may be rescheduled.</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-label">Pending Reschedules</div>
            <div class="stat-value">{{ $pendingReschedules ?? 0 }}</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-label">Approved Today</div>
            <div class="stat-value">{{ $approvedToday ?? 0 }}</div>
        </div>
        <div class="stat-card weekly">
            <div class="stat-label">Total This Week</div>
            <div class="stat-value">{{ $weeklyTotal ?? 0 }}</div>
        </div>
        <div class="stat-card late">
            <div class="stat-label">Late Requests</div>
            <div class="stat-value">{{ $lateRequests ?? 0 }}</div>
        </div>
    </div>

    <!-- Reschedule Requests Table -->
    <div class="content-card">
        <div class="content-header">
            <h3>Reschedule Requests</h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Request Date</th>
                        <th>Patient Name</th>
                        <th>Current Date</th>
                        <th>Requested Date</th>
                        <th>Procedure</th>
                        <th>Days Until Appt</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p style="margin: 8px 0 0 0; font-weight: 500;">No reschedule requests</p>
                                <p style="font-size: 13px; color: #9ca3af;">Pending requests will appear here</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

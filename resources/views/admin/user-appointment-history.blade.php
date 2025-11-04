@extends('layouts.admin')

@section('title', 'User Appointment History')

@section('content')
<style>
    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #0084ff;
    }

    .user-details h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .user-details p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #666;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 20px;
    }

    .stat-card.total .stat-icon {
        background: #e0f2fe;
        color: #0284c7;
    }

    .stat-card.pending .stat-icon {
        background: #fef3c7;
        color: #f59e0b;
    }

    .stat-card.accepted .stat-icon {
        background: #dbeafe;
        color: #3b82f6;
    }

    .stat-card.completed .stat-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .stat-card.declined .stat-icon {
        background: #fee2e2;
        color: #ef4444;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 10px 0 5px;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .content-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .filter-title {
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .filter-dropdown {
        padding: 8px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-dropdown:hover {
        border-color: #0084ff;
    }

    .appointments-list {
        display: grid;
        gap: 20px;
    }

    .appointment-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border-left: 4px solid #0084ff;
        transition: all 0.3s;
    }

    .appointment-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .appointment-card.pending {
        border-left-color: #f59e0b;
    }

    .appointment-card.accepted {
        border-left-color: #3b82f6;
    }

    .appointment-card.completed {
        border-left-color: #10b981;
    }

    .appointment-card.declined {
        border-left-color: #ef4444;
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .appointment-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 5px 0;
    }

    .appointment-procedure {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.accepted {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.declined {
        background: #fee2e2;
        color: #991b1b;
    }

    .appointment-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #4a4a4a;
    }

    .detail-item i {
        color: #0084ff;
        width: 18px;
    }

    .pagination {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background: #f0f0f0;
        color: #1a1a1a;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #e0e0e0;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #ddd;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #666;
        margin: 0 0 10px 0;
    }

    .empty-state p {
        font-size: 14px;
        color: #999;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Appointment History</h1>
    
    <div class="user-info">
        <img src="{{ $user->avatar_url }}" 
             alt="{{ $user->name }}" 
             onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
             class="user-avatar">
        <div class="user-details">
            <h3>{{ $user->name }}</h3>
            <p>{{ $user->email }}</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-value">{{ $totalAppointments }}</div>
        <div class="stat-label">Total Appointments</div>
    </div>

    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $pendingAppointments }}</div>
        <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card accepted">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $acceptedAppointments }}</div>
        <div class="stat-label">Accepted</div>
    </div>

    <div class="stat-card completed">
        <div class="stat-icon">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="stat-value">{{ $completedAppointments }}</div>
        <div class="stat-label">Completed</div>
    </div>

    <div class="stat-card declined">
        <div class="stat-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-value">{{ $declinedAppointments }}</div>
        <div class="stat-label">Declined</div>
    </div>
</div>

<div class="content-card">
    <div class="filter-section">
        <h2 class="filter-title">All Appointments</h2>
        <div>
            <a href="{{ route('admin.patient_information') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Patients
            </a>
        </div>
    </div>

    @if($appointments->isEmpty())
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>No Appointments Found</h3>
            <p>This patient hasn't booked any appointments yet.</p>
        </div>
    @else
        <div class="appointments-list">
            @foreach($appointments as $appointment)
                <div class="appointment-card {{ $appointment->status }}" data-status="{{ $appointment->status }}">
                    <div class="appointment-header">
                        <div>
                            <h3 class="appointment-title">{{ $appointment->title }}</h3>
                            <p class="appointment-procedure">{{ $appointment->procedure }}</p>
                        </div>
                        <span class="status-badge {{ $appointment->status }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="appointment-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end)->format('h:i A') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-hourglass-half"></i>
                            <span>{{ $appointment->duration }} minutes</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-info-circle"></i>
                            <span>Booked {{ $appointment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
@endsection

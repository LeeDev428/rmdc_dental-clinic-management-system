@extends('layouts.admin')

@section('title', 'Service Feedbacks')

@section('content')
<style>
    .page-header {
        background-color: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    
    .content-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: #ffffff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-card.yellow {
        background: #ffffff;
        border-left: 4px solid #f59e0b;
    }
    
    .stat-card.green {
        background: #ffffff;
        border-left: 4px solid #10b981;
    }
    
    .stat-card.blue {
        background: #ffffff;
        border-left: 4px solid #3b82f6;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 4px;
        color: #1f2937;
    }
    
    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead th {
        background-color: #f8f9fa;
        color: #1a1a1a;
        font-weight: 600;
        font-size: 14px;
        text-align: left;
        padding: 12px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .data-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #4a4a4a;
    }
    
    .data-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .stars {
        color: #fbbf24;
        font-size: 18px;
    }
    
    .stars .empty {
        color: #d1d5db;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 24px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Service Feedbacks</h1>
</div>

<div class="content-card">
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $feedbacks->total() }}</div>
            <div class="stat-label">Total Feedbacks</div>
        </div>
        
        <div class="stat-card yellow">
            <div class="stat-value">{{ number_format($feedbacks->avg('rating'), 1) }}</div>
            <div class="stat-label">Average Rating</div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-value">{{ $feedbacks->where('rating', '>=', 4)->count() }}</div>
            <div class="stat-label">Positive Reviews</div>
        </div>
        
        <div class="stat-card blue">
            <div class="stat-value">{{ $feedbacks->where('rating', '<=', 2)->count() }}</div>
            <div class="stat-label">Need Improvement</div>
        </div>
    </div>

    <!-- Feedbacks Table -->
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Appointment</th>
                    <th>Rating</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->created_at->format('M d, Y g:i A') }}</td>
                        <td>
                            <div class="font-semibold">{{ $feedback->user->name }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $feedback->user->id }}</div>
                        </td>
                        <td>
                            <div>{{ $feedback->appointment->title }}</div>
                            <div class="text-xs text-gray-500">{{ $feedback->appointment->procedure }}</div>
                        </td>
                        <td>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $feedback->rating ? '' : 'empty' }}">★</span>
                                @endfor
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $feedback->rating }}/5</div>
                        </td>
                        <td>
                            @if($feedback->comment)
                                <div class="max-w-md">{{ Str::limit($feedback->comment, 100) }}</div>
                            @else
                                <span class="text-gray-400 italic">No comment</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9ca3af; padding: 40px;">
                            No feedbacks yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Reviews')
@section('content')
<style>
    body {
        background-color: #f8f9fa;
        color: #1a1a1a;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 14px;
    }

    .content-wrapper {
        padding: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
        color: #1a1a1a;
    }

    .badge-count {
        background-color: #eff6ff;
        color: #0084ff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .reviews-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .data-table thead th {
        background-color: #f8f9fa;
        color: #4a4a4a;
        font-weight: 600;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .data-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background-color: #fafafa;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rating-number {
        font-weight: 600;
        color: #1a1a1a;
        min-width: 24px;
    }

    .stars {
        color: #fbbf24;
        display: flex;
        gap: 2px;
    }

    .stars i {
        font-size: 14px;
    }

    .star-filled {
        color: #fbbf24;
    }

    .star-empty {
        color: #e0e0e0;
    }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }

    .empty-state i {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #6b7280;
        margin: 0;
        font-size: 14px;
    }

    .card-footer {
        background-color: #fafafa;
        padding: 16px 24px;
        border-top: 1px solid #e0e0e0;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-item {
        display: inline-block;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        color: #4a4a4a;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
    }

    .page-link:hover {
        background-color: #f8f9fa;
        border-color: #0084ff;
        color: #0084ff;
    }

    .page-item.active .page-link {
        background-color: #0084ff;
        border-color: #0084ff;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .page-item.disabled .page-link:hover {
        background-color: transparent;
        border-color: #e0e0e0;
        color: #9ca3af;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Customer Reviews</h1>
        <span class="badge-count">{{ count($reviews) }} Reviews</span>
    </div>

    <div class="reviews-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th style="width: 180px;">Rating</th>
                        <th>Message</th>
                        <th style="width: 140px;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $index => $review)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="rating-display">
                                <span class="rating-number">{{ $review->rating }}</span>
                                <div class="stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star star-filled"></i>
                                        @else
                                            <i class="far fa-star star-empty"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $review->message ?? 'No message provided' }}
                        </td>
                        <td>
                            {{ $review->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="far fa-comment-dots"></i>
                                <p>No reviews found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($reviews) > 0)
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

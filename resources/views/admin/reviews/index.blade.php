@extends('layouts.admin')
@section('title', 'Reviews Management')
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
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
        color: #1a1a1a;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .badge-count {
        background-color: #eff6ff;
        color: #0084ff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-featured {
        background-color: #fef3c7;
        color: #f59e0b;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .filters-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    @media (max-width: 1024px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin: 0;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #4a4a4a;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #0084ff;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background-color: #0084ff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0073e6;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
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
        border-bottom: 2px solid #e0e0e0;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
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

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 14px;
    }

    .user-date {
        font-size: 12px;
        color: #6b7280;
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

    .review-message {
        max-width: 400px;
        color: #4a4a4a;
        line-height: 1.5;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .toggle-featured {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .toggle-featured.featured {
        background-color: #fef3c7;
        border-color: #fbbf24;
        color: #92400e;
    }

    .toggle-featured.not-featured {
        background-color: #f3f4f6;
        border-color: #e0e0e0;
        color: #6b7280;
    }

    .toggle-featured:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

    .pagination-wrapper {
        background-color: #fafafa;
        padding: 16px 24px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        justify-content: center;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-info {
        background-color: #dbeafe;
        border-left: 4px solid #0084ff;
        color: #1e40af;
    }

    .alert-warning {
        background-color: #fef3c7;
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Customer Reviews Management</h1>
        <div class="header-actions">
            <span class="badge-count">
                <i class="fas fa-comments"></i> {{ $reviews->total() }} Total Reviews
            </span>
            <span class="badge-featured">
                <i class="fas fa-star"></i> {{ $featuredCount }}/6 Featured
            </span>
        </div>
    </div>

    @if($featuredCount === 6)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Maximum Featured Reviews Reached</strong>
            <p style="margin: 4px 0 0 0; font-size: 13px;">You have selected the maximum of 6 reviews to display on the homepage. Unfeature a review to select another one.</p>
        </div>
    </div>
    @elseif($featuredCount > 0)
    <div class="alert alert-warning">
        <i class="fas fa-star"></i>
        <div>
            <strong>{{ 6 - $featuredCount }} more review(s) can be featured</strong>
            <p style="margin: 4px 0 0 0; font-size: 13px;">Select up to {{ 6 - $featuredCount }} more review(s) to display on the homepage.</p>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.reviews.index') }}" id="filterForm">
            <div class="filters-grid">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by message or user..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Rating</label>
                    <select name="rating_filter" class="form-select">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating_filter') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating_filter') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating_filter') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating_filter') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating_filter') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Featured Status</label>
                    <select name="featured_filter" class="form-select">
                        <option value="">All Reviews</option>
                        <option value="featured" {{ request('featured_filter') == 'featured' ? 'selected' : '' }}>Featured Only</option>
                        <option value="not_featured" {{ request('featured_filter') == 'not_featured' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Sort By</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Rating</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="reviews-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 220px;">User</th>
                        <th style="width: 140px;">Rating</th>
                        <th>Message</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $index => $review)
                    <tr>
                        <td>{{ $reviews->firstItem() + $index }}</td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ $review->user ? strtoupper(substr($review->user->name, 0, 1)) : '?' }}
                                </div>
                                <div class="user-details">
                                    <span class="user-name">{{ $review->user->name ?? 'Anonymous' }}</span>
                                    <span class="user-date">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </td>
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
                            <div class="review-message">
                                {{ $review->message ?? 'No message provided' }}
                            </div>
                        </td>
                        <td>
                            @if($review->featured)
                                <span class="badge badge-warning">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Active
                                </span>
                            @endif
                        </td>
                        <td>
                            <button 
                                class="toggle-featured {{ $review->featured ? 'featured' : 'not-featured' }}" 
                                onclick="toggleFeatured({{ $review->id }}, this)"
                                data-featured="{{ $review->featured ? 'true' : 'false' }}">
                                <i class="fas {{ $review->featured ? 'fa-star' : 'fa-star-o' }}"></i>
                                <span>{{ $review->featured ? 'Unfeature' : 'Feature' }}</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
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

        @if($reviews->hasPages())
        <div class="pagination-wrapper">
            {{ $reviews->links('vendor.pagination.compact-bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
    function toggleFeatured(reviewId, button) {
        const currentFeatured = button.dataset.featured === 'true';
        const featuredCount = {{ $featuredCount }};
        
        // Check if trying to feature more than 6
        if (!currentFeatured && featuredCount >= 6) {
            alert('You can only feature up to 6 reviews. Please unfeature another review first.');
            return;
        }
        
        fetch(`/admin/reviews/${reviewId}/toggle-featured`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button
                button.dataset.featured = data.featured ? 'true' : 'false';
                button.classList.remove('featured', 'not-featured');
                button.classList.add(data.featured ? 'featured' : 'not-featured');
                
                const icon = button.querySelector('i');
                icon.className = data.featured ? 'fas fa-star' : 'fas fa-star-o';
                
                const span = button.querySelector('span');
                span.textContent = data.featured ? 'Unfeature' : 'Feature';
                
                // Update badge
                const badge = button.closest('tr').querySelector('.badge');
                if (data.featured) {
                    badge.className = 'badge badge-warning';
                    badge.innerHTML = '<i class="fas fa-star"></i> Featured';
                } else {
                    badge.className = 'badge badge-success';
                    badge.innerHTML = '<i class="fas fa-check"></i> Active';
                }
                
                // Show success message and reload to update count
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
</script>
@endsection

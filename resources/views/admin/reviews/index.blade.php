@extends('layouts.admin')
@section('title', 'Reviews')
@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">Customer Reviews</h1>
        </div>
        <div class="col-auto">
            <span class="badge bg-primary rounded-pill px-3 py-2">{{ count($reviews) }} Reviews</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Rating</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $index => $review)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-2">{{ $review->rating }}</span>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
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
                            <td colspan="4" class="text-center py-5">
                                <div class="py-4">
                                    <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 mt-2">No reviews found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(count($reviews) > 0)
        <div class="card-footer bg-white py-3">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm justify-content-center mb-0">
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

<style>
    body {
        background-color: #f8f9fa;
    }

    .table th {
        font-weight: 500;
        font-size: 0.875rem;
        letter-spacing: 0.025rem;
        color: #6c757d;
        border-top: none;
    }

    .table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-color: #f0f0f0;
    }

    .table tr:hover {
        background-color: #f8f9fc;
    }

    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .badge {
        font-weight: 500;
    }

    .pagination .page-link {
        border-radius: 0.25rem;
        margin: 0 2px;
    }

    .pagination .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>
@endsection

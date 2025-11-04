@extends('layouts.admin')

@section('title', 'Procedure Prices')

@section('content')
<style>
    .content-wrapper {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        background-color: #fff;
        padding: 20px 24px;
        margin-bottom: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
        color: #1a1a1a;
    }

    .alert-success {
        background-color: #f0f9f4;
        border: 1px solid #d1f2e0;
        color: #16a34a;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success svg {
        flex-shrink: 0;
    }

    .table-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 24px;
        margin-bottom: 24px;
    }

    .table-container h4 {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 20px 0;
        color: #1a1a1a;
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

    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        border-color: #0084ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,132,255,0.1);
    }

    textarea.form-control {
        min-height: 100px;
         min-width: 180px;
        resize: vertical;
    }

    .input-group {
        display: flex;
        width: 100%;
    }

    .input-group-prepend,
    .input-group-append {
        display: flex;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        padding: 8px 12px;
        font-size: 14px;
        color: #4a4a4a;
    }

    .input-group-prepend .input-group-text {
        border-right: none;
        border-radius: 6px 0 0 6px;
    }

    .input-group-append .input-group-text {
        border-left: none;
        border-radius: 0 6px 6px 0;
    }

    .input-group .form-control {
        border-radius: 0 6px 6px 0;
    }

    .input-group-prepend + .form-control {
        border-left: none;
    }

    .input-group .form-control:not(:last-child) {
        border-radius: 6px 0 0 6px;
        border-right: none;
    }

    .image-preview-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-width: 80px;
    }

    .image-preview {
        width: 100%;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background-color: #fafafa;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 50px;
        object-fit: contain;
    }

    .no-image {
        color: #9ca3af;
        font-size: 12px;
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-start;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background-color: #0084ff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0070e0;
    }

    .btn-success {
        background-color: #16a34a;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #15803d;
    }

    .btn-danger {
        background-color: #ef4444;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .section-divider {
        height: 1px;
        background-color: #e0e0e0;
        margin: 32px 0;
        border: none;
    }

    .add-form-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 24px;
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-card-header h4 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        color: #1a1a1a;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        color: #4a4a4a;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .custom-file {
        position: relative;
        display: block;
    }

    .custom-file-input {
        width: 100%;
        height: 40px;
        opacity: 0;
        position: absolute;
        cursor: pointer;
    }

    .custom-file-label {
        display: block;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background-color: #fff;
        cursor: pointer;
        font-size: 14px;
        color: #6b7280;
    }

    .custom-file-label::after {
        content: 'Browse';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        padding: 8px 16px;
        background-color: #f8f9fa;
        border-left: 1px solid #e0e0e0;
        border-radius: 0 6px 6px 0;
        color: #4a4a4a;
        font-weight: 500;
    }

    .form-text {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 6px;
    }

    .text-center {
        text-align: center;
    }

    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #fff;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .modal-body {
        padding: 24px;
        font-size: 14px;
        color: #4a4a4a;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e0e0e0;
        background-color: #fafafa;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .close {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close:hover {
        color: #4a4a4a;
    }

    /* Floating Add Button */
    .floating-add-btn {
        position: fixed;
        bottom: 32px;
        right: 32px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #16a34a;
        color: #fff;
        border: none;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s;
        z-index: 1000;
    }

    .floating-add-btn:hover {
        background-color: #15803d;
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(22, 163, 74, 0.5);
    }

    .floating-add-btn:active {
        transform: scale(0.95);
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }
    
    /* Search Card */
    .search-card {
        background-color: #fff;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    
    .search-wrapper {
        position: relative;
    }
    
    .search-input {
        width: 100%;
        padding: 10px 12px 10px 40px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #0084ff;
        box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-content h6 {
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        margin: 0 0 8px 0;
    }
    
    .stat-content h3 {
        font-size: 28px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    
    .stat-icon {
        font-size: 32px;
        opacity: 0.2;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-dollar-sign"></i> Procedure Prices
        </h2>
    </div>

    <!-- Display success message with fade-out effect -->
    @if(session('success'))
        <div class="alert alert-success" id="successMessage">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="search-card">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search" class="search-input"
                   placeholder="Search procedures..." onkeyup="searchProcedures()">
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <h6>Total Procedures</h6>
                <h3>{{ count($allProcedures) }}</h3>
            </div>
            <i class="fas fa-procedures stat-icon" style="color: #0084ff;"></i>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <h6>Average Price</h6>
                <h3>₱{{ number_format($allProcedures->avg('price'), 2) }}</h3>
            </div>
            <i class="fas fa-chart-line stat-icon" style="color: #16a34a;"></i>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <h6>Highest Price</h6>
                <h3>₱{{ number_format($allProcedures->max('price'), 2) }}</h3>
            </div>
            <i class="fas fa-arrow-up stat-icon" style="color: #ef4444;"></i>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <h6>Lowest Price</h6>
                <h3>₱{{ number_format($allProcedures->min('price'), 2) }}</h3>
            </div>
            <i class="fas fa-arrow-down stat-icon" style="color: #f59e0b;"></i>
        </div>
    </div>

    <!-- Table to display procedure prices -->
    <div class="table-container">
        <h4>Current Procedures</h4>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Procedure Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procedures as $procedure)
                    <tr>
                        <td style="font-weight: 500;">{{ $procedure->procedure_name }}</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input type="text" id="price_{{ $procedure->id }}" value="{{ old('price', $procedure->price) }}" class="form-control">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" id="duration_{{ $procedure->id }}" value="{{ old('duration', $procedure->duration) }}" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">min</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="image-preview-container">
                                <div class="image-preview">
                                    @if($procedure->image_path)
                                        <img src="{{ asset('storage/' . $procedure->image_path) }}" alt="Procedure Image">
                                    @else
                                        <div class="no-image">No Image</div>
                                    @endif
                                </div>
                                <input type="file" id="image_path_{{ $procedure->id }}" class="form-control-file" style="font-size: 10px; padding: 2px;">
                            </div>
                        </td>
                        <td>
                            <textarea id="description_{{ $procedure->id }}" class="form-control" style="font-size: 10px;">{{ old('description', $procedure->description) }}</textarea>
                        </td>
                        <td>
                            <!-- Action Buttons Container -->
                            <div class="action-buttons">
                                <!-- Update Button -->
                                <button class="btn btn-primary btn-sm" onclick="confirmUpdate({{ $procedure->id }})">
                                    <i class="fas fa-save"></i> Update
                                </button>

                                <!-- Delete Button -->
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $procedure->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>

                            <!-- Update Form -->
                            <form id="updateForm_{{ $procedure->id }}" action="{{ route('admin.procedure_prices.update', ['id' => $procedure->id]) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="price" id="hidden_price_{{ $procedure->id }}">
                                <input type="hidden" name="duration" id="hidden_duration_{{ $procedure->id }}">
                                <input type="hidden" name="description" id="hidden_description_{{ $procedure->id }}">
                                <input type="file" name="image_path" id="hidden_image_path_{{ $procedure->id }}">
                            </form>

                            <!-- Delete Form -->
                            <form id="deleteForm_{{ $procedure->id }}" action="{{ route('admin.procedure_prices.destroy', ['id' => $procedure->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $procedures->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Floating Add Button -->
<button type="button" class="floating-add-btn" onclick="$('#addProcedureModal').modal('show')">
    <i class="fas fa-plus"></i>
</button>

<!-- Add New Procedure Modal -->
<div class="modal fade" id="addProcedureModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle" style="color: #16a34a; margin-right: 8px;"></i>
                    Add New Procedure
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProcedureForm" action="{{ route('admin.procedure_prices.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_procedure_name">Procedure Name</label>
                                <input type="text" name="procedure_name" id="modal_procedure_name" class="form-control" placeholder="Enter procedure name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modal_price">Price (₱)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="text" name="price" id="modal_price" class="form-control" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="modal_duration">Duration (minutes)</label>
                                <div class="input-group">
                                    <input type="text" name="duration" id="modal_duration" class="form-control" placeholder="30" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_image_path">Procedure Image</label>
                                <div class="custom-file">
                                    <input type="file" name="image_path" id="modal_image_path" class="custom-file-input">
                                    <label class="custom-file-label" for="modal_image_path">Choose image...</label>
                                </div>
                                <small class="form-text">Recommended size: 600x400px</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_description">Description</label>
                                <textarea name="description" id="modal_description" class="form-control" rows="4" placeholder="Enter procedure description..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="document.getElementById('addProcedureForm').submit()">
                    <i class="fas fa-plus-circle"></i> Add Procedure
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Bootstrap Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Confirmation</h5>
                <button type="button" class="close" onclick="$('#confirmationModal').modal('hide')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalMessage">Are you sure?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#confirmationModal').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Yes, Proceed</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Function to handle file input display
    document.addEventListener('DOMContentLoaded', function() {
        // Handle the custom file input for main form
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose image...');
        });

        // Handle the custom file input for modal form
        $('#modal_image_path').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose image...');
        });
    });

    function confirmUpdate(id) {
        document.getElementById('modalTitle').innerText = 'Update Confirmation';
        document.getElementById('modalMessage').innerText = 'Are you sure you want to update this procedure price?';

        document.getElementById('confirmAction').onclick = function() {
            document.getElementById('hidden_price_' + id).value = document.getElementById('price_' + id).value;
            document.getElementById('hidden_duration_' + id).value = document.getElementById('duration_' + id).value;
            document.getElementById('hidden_description_' + id).value = document.getElementById('description_' + id).value;
            const imageInput = document.getElementById('image_path_' + id);
            if (imageInput.files.length > 0) {
                document.getElementById('hidden_image_path_' + id).files = imageInput.files;
            }

            document.getElementById('updateForm_' + id).submit();
        };

        $('#confirmationModal').modal('show');
    }

    function confirmDelete(id) {
        document.getElementById('modalTitle').innerText = 'Delete Confirmation';
        document.getElementById('modalMessage').innerText = 'Are you sure you want to delete this procedure price? This action cannot be undone.';

        document.getElementById('confirmAction').onclick = function() {
            document.getElementById('deleteForm_' + id).submit();
        };

        $('#confirmationModal').modal('show');
    }

    // Auto-hide success message with animation
    setTimeout(() => {
        let successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            successMessage.style.opacity = '0';
            successMessage.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }, 500);
        }
    }, 5000);
    
    // Search function
    function searchProcedures() {
        const searchInput = document.getElementById('search').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.data-table tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchInput)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show message if no results found
        const tbody = document.querySelector('.data-table tbody');
        let noResultsRow = document.getElementById('no-results-row');
        
        if (visibleCount === 0 && searchInput !== '') {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.id = 'no-results-row';
                noResultsRow.innerHTML = '<td colspan="6" style="text-align: center; padding: 40px; color: #9ca3af;"><i class="fas fa-search"></i> No procedures found matching "' + searchInput + '"</td>';
                tbody.appendChild(noResultsRow);
            }
        } else {
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }
    }, 3000);
</script>

@endsection

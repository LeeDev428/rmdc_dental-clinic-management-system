@extends('layouts.admin')

@section('title', 'Procedure Prices')

<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2ecc71;
        --danger-color: #e74c3c;
        --light-bg: #f8f9fa;
        --dark-text: #2c3e50;
        --card-shadow: 0 4px 8px rgba(0,0,0,0.1);
        --border-radius: 8px;
        --transition-speed: 0.3s;
    }

    body {
        background-color: #f0f5f9 !important;
        color: var(--dark-text);
        font-family: 'Nunito', sans-serif;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), #2980b9);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
        margin-top: 30px;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
    }

    .page-title {
        font-weight: 700;
        margin: 0;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        overflow: hidden;
        transition: transform var(--transition-speed), box-shadow var(--transition-speed);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .card-header {
        padding: 15px 20px;
        font-weight: 600;
    }

    .table-container {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin-bottom: 30px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f1f8ff;
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding: 12px 15px;
        font-weight: 600;
    }

    .table td {
        padding: 15px;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: background-color var(--transition-speed);
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        transition: all var(--transition-speed);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    textarea.form-control {
        min-height: 100px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn {
        border-radius: 6px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all var(--transition-speed);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-success {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .btn-success:hover {
        background-color: #27ae60;
        border-color: #27ae60;
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 14px;
    }

    .image-preview-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .image-preview {
        width: 100%;
        height: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        border-radius: var(--border-radius);
        background-color: #f8f9fa;
        transition: all var(--transition-speed);
    }

    .image-preview:hover {
        border-color: var(--primary-color);
    }

    .image-preview img {
        max-width: 100%;
        max-height: 120px;
        object-fit: contain;
    }

    .no-image {
        color: #aaa;
        font-style: italic;
    }

    .file-upload-control {
        position: relative;
    }

    .file-upload-control input[type="file"] {
        cursor: pointer;
        font-size: 14px;
    }

    .alert {
        border-radius: var(--border-radius);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    /* Modal styling */
    .modal-content {
        border-radius: var(--border-radius);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        border: none;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #eee;
    }

    .section-divider {
        height: 3px;
        background: linear-gradient(to right, transparent, var(--primary-color), transparent);
        margin: 40px 0;
        border: none;
    }

    .form-group label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }
</style>

@section('content')
<div class="container">
    <div class="page-header text-center">
        <h2 class="page-title">Procedure Prices</h2>
    </div>

    <!-- Display success message with fade-out effect -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" id="successMessage">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill flex-shrink-0 me-2" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Table to display procedure prices -->
    <div class="table-container">
        <h4 class="mb-4">Current Procedures</h4>
        <div class="table-responsive">
            <table class="table table-hover">
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
                        <td class="font-weight-bold">{{ $procedure->procedure_name }}</td>
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
                                <div class="file-upload-control">
                                    <input type="file" id="image_path_{{ $procedure->id }}" class="form-control-file form-control-sm">
                                </div>
                            </div>
                        </td>
                        <td>
                            <textarea id="description_{{ $procedure->id }}" class="form-control">{{ old('description', $procedure->description) }}</textarea>
                        </td>
                        <td>
                            <!-- Action Buttons Container -->
                            <div class="action-buttons">
                                <!-- Update Button -->
                                <button class="btn btn-primary btn-sm" onclick="confirmUpdate({{ $procedure->id }})">
                                    <i class="fas fa-save mr-1"></i> Update
                                </button>

                                <!-- Delete Button -->
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $procedure->id }})">
                                    <i class="fas fa-trash mr-1"></i> Delete
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
    </div>

    <hr class="section-divider">

    <!-- Form to Create New Procedure Price -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-plus-circle mr-2"></i> Add New Procedure</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.procedure_prices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="procedure_name">Procedure Name</label>
                            <input type="text" name="procedure_name" id="procedure_name" class="form-control" placeholder="Enter procedure name" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price">Price (₱)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"></span>
                                </div>
                                <input type="text" name="price" id="price" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="duration">Duration (minutes)</label>
                            <div class="input-group">
                                <input type="text" name="duration" id="duration" class="form-control" placeholder="30" required>
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
                            <label for="image_path">Procedure Image</label>
                            <div class="custom-file">
                                <input type="file" name="image_path" id="image_path" class="custom-file-input">
                                <label class="custom-file-label" for="image_path">Choose image...</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 600x400px</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter procedure description..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle mr-1"></i> Add New Procedure
                    </button>
                </div>
            </form>
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
        // Handle the custom file input
        $('.custom-file-input').on('change', function() {
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
    }, 3000);
</script>

<!-- FontAwesome, Bootstrap & jQuery -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection

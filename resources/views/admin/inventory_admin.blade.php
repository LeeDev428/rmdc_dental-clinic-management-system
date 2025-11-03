@extends('layouts.admin')

@section('title', 'Inventory Management')

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
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .search-card {
        background-color: #fff;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    
    .search-input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .search-wrapper {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
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
    
    .content-grid {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 24px;
        margin-bottom: 50px;
    }
    
    .form-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 0;
        height: fit-content;
    }
    
    .form-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .form-header h5 {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-body {
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #0084ff;
    }
    
    .btn-submit {
        width: 100%;
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-submit:hover {
        background-color: #0073e6;
    }
    
    .table-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead th {
        background-color: #f8f9fa;
        color: #1a1a1a;
        font-weight: 600;
        font-size: 13px;
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
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-instock {
        background-color: #f0fdf4;
        color: #16a34a;
    }
    
    .status-low {
        background-color: #fff4e6;
        color: #d97706;
    }
    
    .status-critical {
        background-color: #fee;
        color: #c41e3a;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit, .btn-delete {
        padding: 4px 12px;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-edit {
        background-color: #0ea5e9;
        color: #fff;
    }
    
    .btn-edit:hover {
        background-color: #0284c7;
    }
    
    .btn-delete {
        background-color: #ef4444;
        color: #fff;
    }
    
    .btn-delete:hover {
        background-color: #dc2626;
    }
    
    @media (max-width: 1200px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-boxes"></i>
        Inventory Management
    </h1>
</div>

<!-- Search Bar -->
<div class="search-card">
    <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="search" class="search-input"
               placeholder="Search inventory items..." onkeyup="searchInventory()">
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <h6>Total Items</h6>
            <h3>{{ count($inventory) }}</h3>
        </div>
        <i class="fas fa-clipboard-list stat-icon" style="color: #0084ff;"></i>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h6>In Stock</h6>
            <h3>{{ $inventory->where('quantity', '>', 0)->count() }}</h3>
        </div>
        <i class="fas fa-check-circle stat-icon" style="color: #16a34a;"></i>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h6>Low Stock</h6>
            <h3>{{ $inventory->where('quantity', '<', 10)->where('quantity', '>', 0)->count() }}</h3>
        </div>
        <i class="fas fa-exclamation-triangle stat-icon" style="color: #d97706;"></i>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h6>Out of Stock</h6>
            <h3>{{ $inventory->where('quantity', '=', 0)->count() }}</h3>
        </div>
        <i class="fas fa-times-circle stat-icon" style="color: #ef4444;"></i>
    </div>
</div>

<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Add New Item Form -->
    <div class="form-card">
        <div class="form-header">
            <h5>
                <i class="fas fa-plus-circle"></i>
                Add New Item
            </h5>
        </div>
        <div class="form-body">
            <form action="{{ route('admin.inventory_admin.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control"
                           placeholder="Enter item name" required>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" class="form-control"
                           placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control"
                           placeholder="0" min="0" required>
                </div>

                <div class="form-group">
                    <label for="expiration_type">Expiration Type:</label>
                    <select id="expiration_type" name="expiration_type" class="form-control"
                            required onchange="toggleExpirationField()">
                        <option value="expirable">Expirable</option>
                        <option value="inexpirable">Inexpirable</option>
                    </select>
                </div>

                <div class="form-group" id="expiration_date_group">
                    <label for="expiration_date">Expiration Date:</label>
                    <input type="date" id="expiration_date" name="expiration_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="supplier">Supplier:</label>
                    <input type="text" id="supplier" name="supplier" class="form-control"
                           placeholder="Enter supplier name">
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Patient Care Supplies">Patient Care Supplies</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Dental Instruments">Dental Instruments</option>
                        <option value="Sterilization Products">Sterilization Products</option>
                        <option value="Surgical Tools">Surgical Tools</option>
                        <option value="Protective Gear">Protective Gear</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus-circle"></i> Add Item
                </button>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Expiration</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="inventoryTable">
                    @foreach ($inventory as $item)
                        <tr class="inventory-item">
                            <td>
                                <strong>{{ $item->name }}</strong>
                                <div style="font-size: 12px; color: #9ca3af;">From: {{ $item->supplier ?: 'Unknown' }}</div>
                            </td>
                            <td>₱{{ $item->price }}</td>
                            <td>
                                @if($item->quantity > 20)
                                    <span class="status-badge status-instock">{{ $item->quantity }}</span>
                                @elseif($item->quantity > 0)
                                    <span class="status-badge status-low">{{ $item->quantity }}</span>
                                @else
                                    <span class="status-badge status-critical">Out of stock</span>
                                @endif
                            </td>
                            <td>
                                @if($item->expiration_date)
                                    @php
                                        $expirationDate = \Carbon\Carbon::parse($item->expiration_date);
                                        $now = \Carbon\Carbon::now();
                                        $daysUntilExpiration = $now->diffInDays($expirationDate, false);
                                    @endphp

                                    @if($daysUntilExpiration < 0)
                                        <span class="status-badge status-critical">Expired</span>
                                    @elseif($daysUntilExpiration < 30)
                                        <span class="status-badge status-low">{{ $item->expiration_date }}</span>
                                    @else
                                        <span style="font-size: 13px; color: #4a4a4a;">{{ $item->expiration_date }}</span>
                                    @endif
                                @else
                                    <span style="font-size: 13px; color: #9ca3af;">N/A</span>
                                @endif
                            </td>
                            <td>{{ $item->category }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" data-toggle="modal" data-target="#editModal"
                                        onclick="editItem({{ $item->id }}, '{{ $item->name }}', '{{ $item->price }}', '{{ $item->quantity }}', '{{ $item->expiration_date }}', '{{ $item->supplier }}', '{{ $item->category }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn-delete" data-toggle="modal" data-target="#deleteModal"
                                        onclick="setDeleteAction('{{ route('admin.inventory_admin.destroy', $item->id) }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Edit Item -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i> Update Item
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="update_name" class="form-label">Name:</label>
                        <input type="text" id="update_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_price" class="form-label">Price:</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="text" id="update_price" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_quantity" class="form-label">Quantity:</label>
                        <input type="number" id="update_quantity" name="quantity" class="form-control" min="0" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_expiration_type" class="form-label">Expiration Type:</label>
                        <select id="update_expiration_type" name="expiration_type" class="form-select" onchange="toggleUpdateExpirationField()">
                            <option value="expirable">Expirable</option>
                            <option value="inexpirable">Inexpirable</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="update_expiration_date_group">
                        <label for="update_expiration_date" class="form-label">Expiration Date:</label>
                        <input type="date" id="update_expiration_date" name="expiration_date" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_supplier" class="form-label">Supplier:</label>
                        <input type="text" id="update_supplier" name="supplier" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="update_category" class="form-label">Category:</label>
                        <select id="update_category" name="category" class="form-select" required>
                            <option value="Patient Care Supplies">Patient Care Supplies</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Dental Instruments">Dental Instruments</option>
                            <option value="Sterilization Products">Sterilization Products</option>
                            <option value="Surgical Tools">Surgical Tools</option>
                            <option value="Protective Gear">Protective Gear</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this inventory item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to toggle expiration date field based on expiration type
    function toggleExpirationField() {
        const expirationType = document.getElementById('expiration_type').value;
        const expirationDateGroup = document.getElementById('expiration_date_group');
        const expirationDateInput = document.getElementById('expiration_date');

        if (expirationType === 'inexpirable') {
            expirationDateInput.disabled = true;
            expirationDateInput.required = false;
        } else {
            expirationDateInput.disabled = false;
            expirationDateInput.required = true;
        }
    }

    // Function to toggle update form expiration date field
    function toggleUpdateExpirationField() {
        const expirationType = document.getElementById('update_expiration_type').value;
        const expirationDateInput = document.getElementById('update_expiration_date');

        if (expirationType === 'inexpirable') {
            expirationDateInput.disabled = true;
            expirationDateInput.required = false;
        } else {
            expirationDateInput.disabled = false;
            expirationDateInput.required = true;
        }
    }

    // Function to search inventory items
    function searchInventory() {
        const searchInput = document.getElementById('search').value.toLowerCase();
        const rows = document.getElementsByClassName('inventory-item');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();

            if (text.includes(searchInput)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    // Function to set up item editing
    function editItem(id, name, price, quantity, expirationDate, supplier, category) {
        document.getElementById('update_name').value = name;
        document.getElementById('update_price').value = price;
        document.getElementById('update_quantity').value = quantity;
        document.getElementById('update_expiration_date').value = expirationDate;
        document.getElementById('update_supplier').value = supplier;

        // Set category in dropdown
        const categorySelect = document.getElementById('update_category');
        for (let i = 0; i < categorySelect.options.length; i++) {
            if (categorySelect.options[i].value === category) {
                categorySelect.selectedIndex = i;
                break;
            }
        }

        // Set expiration type based on whether there's an expiration date
        const expirationTypeSelect = document.getElementById('update_expiration_type');
        expirationTypeSelect.value = expirationDate ? 'expirable' : 'inexpirable';
        toggleUpdateExpirationField();

        // Set the form action URL
        const formAction = '/admin/inventory-admin/update/' + id;
        document.getElementById('updateForm').action = formAction;
    }

    // Function to set delete action
    function setDeleteAction(url) {
        document.getElementById('deleteForm').action = url;
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Initial call to toggle expiration field
        toggleExpirationField();
    });
</script>

<!-- Add Font Awesome if not already included in the layout -->
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"></script>
@endsection

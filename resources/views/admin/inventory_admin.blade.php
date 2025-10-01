@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('content')
<div class="container py-4" style="margin-bottom: 50px;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h1 class="card-title mb-0 py-2">
                        <i class="fas fa-boxes me-2"></i> Inventory Management
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar in Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body bg-light">
            <div class="form-group mb-0">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="text" id="search" class="form-control border-start-0"
                           placeholder="Search inventory items..." onkeyup="searchInventory()">
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Items</h6>
                            <h3 class="mb-0">{{ count($inventory) }}</h3>
                        </div>
                        <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">In Stock</h6>
                            <h3 class="mb-0">{{ $inventory->where('quantity', '>', 0)->count() }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Low Stock</h6>
                            <h3 class="mb-0">{{ $inventory->where('quantity', '<', 10)->where('quantity', '>', 0)->count() }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Out of Stock</h6>
                            <h3 class="mb-0">{{ $inventory->where('quantity', '=', 0)->count() }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Add Form and Table -->
    <div class="row">
        <!-- First column: Add New Item Form -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Add New Item
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.inventory_admin.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="Enter item name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price" class="form-label">Price:</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="text" id="price" name="price" class="form-control"
                                       placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" class="form-control"
                                   placeholder="0" min="0" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="expiration_type" class="form-label">Expiration Type:</label>
                            <select id="expiration_type" name="expiration_type" class="form-select"
                                    required onchange="toggleExpirationField()">
                                <option value="expirable">Expirable</option>
                                <option value="inexpirable">Inexpirable</option>
                            </select>
                        </div>

                        <div class="form-group mb-3" id="expiration_date_group">
                            <label for="expiration_date" class="form-label">Expiration Date:</label>
                            <input type="date" id="expiration_date" name="expiration_date" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="supplier" class="form-label">Supplier:</label>
                            <input type="text" id="supplier" name="supplier" class="form-control"
                                   placeholder="Enter supplier name">
                        </div>

                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Category:</label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="Patient Care Supplies">Patient Care Supplies</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Dental Instruments">Dental Instruments</option>
                                <option value="Sterilization Products">Sterilization Products</option>
                                <option value="Surgical Tools">Surgical Tools</option>
                                <option value="Protective Gear">Protective Gear</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-1"></i> Add Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Second column: Inventory Table -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i> Inventory Items
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">Name</th>
                                    <th class="px-3">Price</th>
                                    <th class="px-3">Qty</th>
                                    <th class="px-3">Expiration</th>
                                    <th class="px-3">Category</th>
                                    <th class="px-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTable">
                                @foreach ($inventory as $item)
                                    <tr class="inventory-item">
                                        <td class="px-3">
                                            <strong>{{ $item->name }}</strong>
                                            <div class="small text-muted">From: {{ $item->supplier ?: 'Unknown' }}</div>
                                        </td>
                                        <td class="px-3">${{ $item->price }}</td>
                                        <td class="px-3">
                                            @if($item->quantity > 20)
                                                <span class="badge bg-success">{{ $item->quantity }}</span>
                                            @elseif($item->quantity > 0)
                                                <span class="badge bg-warning text-dark">{{ $item->quantity }}</span>
                                            @else
                                                <span class="badge bg-danger">Out of stock</span>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            @if($item->expiration_date)
                                                @php
                                                    $expirationDate = \Carbon\Carbon::parse($item->expiration_date);
                                                    $now = \Carbon\Carbon::now();
                                                    $daysUntilExpiration = $now->diffInDays($expirationDate, false);
                                                @endphp

                                                @if($daysUntilExpiration < 0)
                                                    <span class="badge bg-danger">Expired</span>
                                                @elseif($daysUntilExpiration < 30)
                                                    <span class="badge bg-warning text-dark">{{ $item->expiration_date }}</span>
                                                @else
                                                    <span class="badge bg-info">{{ $item->expiration_date }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            <span class="badge bg-primary">{{ $item->category }}</span>
                                        </td>
                                        <td class="px-3">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editModal"
                                                    onclick="editItem({{ $item->id }}, '{{ $item->name }}', '{{ $item->price }}', '{{ $item->quantity }}', '{{ $item->expiration_date }}', '{{ $item->supplier }}', '{{ $item->category }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#deleteModal"
                                                    onclick="setDeleteAction('{{ route('admin.inventory_admin.destroy', $item->id) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i> Showing {{ count($inventory) }} total items
                    </small>
                </div>
            </div>
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

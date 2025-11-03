@extends('layouts.admin')
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
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
    
    .management-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .management-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .management-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .card-content {
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .card-info h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 4px 0;
    }
    
    .card-info p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    
    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f9ff;
    }
    
    .card-icon svg {
        width: 24px;
        height: 24px;
        color: #0084ff;
    }
    
    .card-footer {
        padding: 12px 24px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .card-footer span {
        font-size: 13px;
        color: #0084ff;
        font-weight: 500;
    }
    
    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-danger {
        background-color: #fee;
        color: #c41e3a;
    }
    
    .badge-secondary {
        background-color: #f3f4f6;
        color: #6b7280;
    }
    
    .arrow-icon {
        color: #9ca3af;
        font-size: 14px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">RMDC Management</h1>
</div>

<div class="management-grid">
    <!-- Patient Messages Card -->
    <div class="management-card message-card" onclick="window.location='{{ route('admin.patient_messages') }}'">
        <div class="card-content">
            <div class="card-info">
                <h3>Patient Messages</h3>
                <p>View all messages</p>
            </div>
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/>
                </svg>
            </div>
        </div>
        <div class="card-footer">
            <span>View Details</span>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span id="unreadMessagesBadge" class="badge badge-secondary">0</span>
                <i class="fas fa-angle-right arrow-icon"></i>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateUnreadMessagesCount() {
            $.ajax({
                url: "{{ url('/admin/unread-messages-count') }}",
                method: "GET",
                success: function(response) {
                    let badge = $("#unreadMessagesBadge");
                    if (response.count > 0) {
                        badge.text(response.count).removeClass("bg-secondary").addClass("bg-danger");
                    } else {
                        badge.text("0").removeClass("bg-danger").addClass("bg-secondary");
                    }
                },
                error: function() {
                    console.error("Failed to fetch unread message count.");
                }
            });
        }

        function markMessagesAsRead() {
            $.ajax({
                url: "{{ url('/admin/mark-messages-read') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        updateUnreadMessagesCount(); // Refresh the unread message count
                    }
                },
                error: function() {
                    console.error("Failed to mark messages as read.");
                }
            });
        }

        $(document).ready(function () {
            updateUnreadMessagesCount();

            $(".message-card").on("click", function () {
                markMessagesAsRead(); // Mark messages as read when clicked
            });

            setInterval(updateUnreadMessagesCount, 5000); // Auto-refresh every 5 seconds
        });
    </script>

    <!-- User Information Card -->
    <div class="management-card" onclick="window.location='{{ route('admin.patient_information') }}'">
        <div class="card-content">
            <div class="card-info">
                <h3>User Information</h3>
                <p>Manage users</p>
            </div>
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
        <div class="card-footer">
            <span>View Details</span>
            <div style="display: flex; align-items: center; gap: 8px;">
                @if($userCount > 0)
                    <span class="badge badge-danger">{{ $userCount }}</span>
                @else
                    <span class="badge badge-secondary">0</span>
                @endif
                <i class="fas fa-angle-right arrow-icon"></i>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments Card -->
    <div class="management-card" onclick="window.location='{{ route('admin.appointments') }}'">
        <div class="card-content">
            <div class="card-info">
                <h3>Upcoming Appt/s</h3>
                <p>Scheduled appointments</p>
            </div>
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V10h14v9zm0-11H5V5h14v3zM7 12h5v5H7v-5z"/>
                </svg>
            </div>
        </div>
        <div class="card-footer">
            <span>View Details</span>
            <div style="display: flex; align-items: center; gap: 8px;">
                @if($upcomingCount > 0)
                    <span class="badge badge-danger">{{ $upcomingCount }}</span>
                @else
                    <span class="badge badge-secondary">0</span>
                @endif
                <i class="fas fa-angle-right arrow-icon"></i>
            </div>
        </div>
    </div>

    <!-- Pending Appointments Card -->
    <div class="management-card" onclick="window.location='{{ route('admin.upcoming_appointments') }}'">
        <div class="card-content">
            <div class="card-info">
                <h3>Pending Appt/s</h3>
                <p>Awaiting approval</p>
            </div>
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>
        </div>
        <div class="card-footer">
            <span>View Details</span>
            <div style="display: flex; align-items: center; gap: 8px;">
                @if($pendingCount > 0)
                    <span class="badge badge-danger" id="pending-count-card">{{ $pendingCount }}</span>
                @else
                    <span class="badge badge-secondary">0</span>
                @endif
                <i class="fas fa-angle-right arrow-icon"></i>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updatePendingCount() {
            $.ajax({
                url: "{{ route('notifications.pending-count') }}", // AJAX route
                method: "GET",
                success: function(response) {
                    let count = response.pendingCount;

                    // Update Notification Bell
                    let bellBadge = $(".notification-count");
                    if (count > 0) {
                        bellBadge.text(count).show();
                    } else {
                        bellBadge.hide();
                    }

                    // Update Pending Appointments Card
                    let cardBadge = $("#pending-count-card");
                    if (count > 0) {
                        cardBadge.text(count).show();
                    } else {
                        cardBadge.hide();
                    }
                },
                error: function(xhr) {
                    console.log("Error fetching pending count:", xhr);
                }
            });
        }

        // Call function every 10 seconds
        setInterval(updatePendingCount, 10000);

        // Initial call when page loads
        $(document).ready(function() {
            updatePendingCount();
        });
    </script>



    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Edit Availability
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8; margin-left: 10px;">
                    <path d="M3 17.25V21h3.75L15.56 13.84l-3.75-3.75L3 17.25zm16.74-9.19l-2.56-2.56a1 1 0 0 0-1.41 0L13.64 8.06l3.75 3.75 3.75-3.75a1 1 0 0 0 0-1.41z"/>
                </svg>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="#">View Details</a>
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Inventory
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8; margin-left: 10px;">
                    <path d="M7 4h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1zm10 2H7v12h10V6zM12 8h2v2h-2zm0 4h2v2h-2zm-4-4h2v2H8zm0 4h2v2H8z"/>
                </svg>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="{{ route('admin.inventory_admin') }}">View Details</a>
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Patient Alerts
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8; margin-left: 10px;">
                    <path d="M12 22c1.1 0 1.99-.9 1.99-2H10c0 1.1.9 2 2 2zm6-3V9c0-3.31-2.69-6-6-6S6 5.69 6 9v10h12zm-3-3h-6v-7h6v7z"/>
                </svg>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="#">View Details</a>
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Dental Prices
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
    style="width: 28px; height: 28px; color: #1d4ed8; margin-left: 10px;"
    aria-label="Money Icon">
    <path d="M12 1C6.48 1 2 5.48 2 11s4.48 10 10 10 10-4.48 10-10S17.52 1 12 1zm1 15h-2v-1c-1.1 0-2-.9-2-2h2c0 .55.45 1 1 1s1-.45 1-1-.45-1-1-1c-1.65 0-3-1.35-3-3s1.35-3 3-3V5h2v1c1.1 0 2 .9 2 2h-2c0-.55-.45-1-1-1s-1 .45-1 1 .45 1 1 1c1.65 0 3 1.35 3 3s-1.35 3-3 3v1z"/>
</svg>

            </div>
            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="{{ route('admin.procedure_prices') }}">View Details</a>
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>


    <style>
        .card:hover {
            transform: scale(1.04);
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
    </style>
@endsection

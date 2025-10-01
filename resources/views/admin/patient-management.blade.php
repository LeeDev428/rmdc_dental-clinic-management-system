@extends('layouts.admin')
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
@section('content')
<h1 class="mt-4">RMDC Management</h1>

<br>
<div class="row">

    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 message-card" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); cursor: pointer;">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Patient Messages
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8;">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2-2-2V6c0-1.1-.9-2-.9-2zm-2 10h-5l-3 3-3-3H4V6h16v8z"/>
                </svg>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="{{ route('admin.patient_messages') }}">View Details</a>
                <span id="unreadMessagesBadge" class="badge bg-secondary">0</span>
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
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



    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                User Information
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8;">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4a2 2 0 110 4 2 2 0 010-4zm0 14c-3.87 0-7-1.57-7-3.5S8.13 13 12 13s7 1.57 7 3.5S15.87 20 12 20z"/>
                </svg>

            </div>

            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="{{ route('admin.patient_information') }}">View Details</a>
                @if($userCount > 0)
                <span class="badge bg-danger">{{ $userCount }}</span>
            @else
                <span class="badge bg-secondary">0</span>
            @endif
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>




    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Upcoming Appt/s
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8; margin-left: 10px;">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V10h14v9zm0-11H5V5h14v3zM7 12h5v5H7v-5z"/>
                </svg>

            </div>
            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="{{ route('admin.appointments') }}">View Details</a>
                @if($upcomingCount > 0)
                <span class="badge bg-danger">{{ $upcomingCount }}</span>
            @else
                <span class="badge bg-secondary">0</span>
            @endif
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>



    <div class="col-xl-3 col-md-6">
        <div class="card mb-4" style="background-color: #d1e7fd; color: black; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="font-family: 'Source Sans Pro', sans-serif; font-size: 20px; font-weight: 500; display: flex; align-items: center; justify-content: space-between;">
                Pending Appt/s
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 28px; height: 28px; color: #1d4ed8; margin-left: 10px;">
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>

            <div class="card-footer d-flex align-items-center justify-content-between" style="border-top: 1px solid #cbd5e1;">
                <a class="small text-black stretched-link" href="{{ route('admin.upcoming_appointments') }}">View Details</a>
                @if($pendingCount > 0)
                <span class="badge bg-danger" id="pending-count-card">{{ $pendingCount }}</span>
            @else
                <span class="badge bg-secondary">0</span>
            @endif
                <div class="small text-black"><i class="fas fa-angle-right"></i></div>
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

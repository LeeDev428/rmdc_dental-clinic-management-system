<style>
    .sidebarToggle {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1000;
    }

    .notification-bell {
        position: relative;
        margin-right: 20px;
        color: white;
        font-size: 20px;
        cursor: pointer;
        top: 12px;
    }
    .notification-bell .badge {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: red;
        color: white;
        font-size: 12px;
        border-radius: 50%;
        padding: 3px 6px;
    }
</style>

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ url('admin/dashboard') }}">RMDC</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm sidebar-toggle-btn" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <!-- Notification Bell
            <li class="nav-item">
                <a class="nav-link notification-bell" href="{{ route('admin.upcoming_appointments') }}">
                    <i class="fas fa-bell"></i>
                    <span id="pending-count-badge" class="badge notification-count" style="display: none;">0</span>
                </a>
            </li>-->

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                function updatePendingCount() {
                    $.ajax({
                        url: "{{ route('notifications.pending-count') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            let badge = $("#pending-count-badge");
                            if (response.pendingCount > 0) {
                                badge.text(response.pendingCount).show().removeClass('bg-secondary').addClass('bg-danger');
                            } else {
                                badge.hide(); // Hide the badge if there are no pending appointments
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching pending count:", error);
                        }
                    });
                }

                // ✅ Fetch count every 5 seconds
                setInterval(updatePendingCount, 5000);

                // ✅ Fetch count when page loads
                $(document).ready(function() {
                    updatePendingCount();
                });
            </script>



            <!-- Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- User Profile Image -->
                    <img src="{{ Auth::user()->avatar_url }}" 
                         alt="{{ Auth::user()->name }}" 
                         onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                         style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 2px solid #fff;">
                    <!-- Display Username -->
                    {{-- <span>{{ Auth::user()->name }}</span> --}}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="fas fa-user-edit me-2"></i>Edit Profile</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i>{{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    @vite('resources/js/app.js')
    <script>
        // Check if Echo is available before using it
        if (typeof Echo !== 'undefined') {
            Echo.channel('appointments')
                .listen('AppointmentStatusChanged', (e) => {
                    fetchNotifications();
                });
        }

        const fetchNotifications = async () => {
            try {
                const response = await fetch('/get-unread-count');
                
                // Check if response is JSON or HTML (redirect)
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Not JSON, probably a redirect to login - silently skip
                    return;
                }
                
                const data = await response.json();
                const notificationCount = document.querySelector('.notification-count');
                if (data.unreadCount > 0) {
                    notificationCount.textContent = data.unreadCount;
                    notificationCount.style.display = "block";
                } else {
                    notificationCount.style.display = "none";
                }
            } catch (error) {
                // Silently fail - user might not be logged in
                console.debug("Could not fetch notifications (user may not be authenticated)");
            }
        };

        document.addEventListener("DOMContentLoaded", () => {
            fetchNotifications();
            setInterval(fetchNotifications, 10000);
        });

        const markAsRead = async () => {
            try {
                const response = await fetch('/mark-notifications-as-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                if (response.ok) {
                    // Update notification count UI
                    const notificationCount = document.querySelector('.notification-count');
                    notificationCount.textContent = "0";
                    notificationCount.style.display = "none";
                    // Optionally, hide the "unread" notifications from the UI or refresh the notifications list
                    const notificationItems = document.querySelectorAll('.notification-item');
                    notificationItems.forEach(item => {
                        item.classList.remove('unread'); // Assuming 'unread' is a class indicating unread notifications
                    });
                }
            } catch (error) {
                console.error("Error marking notifications as read:", error);
            }
        };

        // Only add listener if notification bell exists
        const bellElement = document.querySelector('.notification-bell');
        if (bellElement) {
            bellElement.addEventListener('click', markAsRead);
        }
    </script>

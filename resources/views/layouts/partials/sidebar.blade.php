<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <div class="sb-sidenav-menu-heading">RMDC Management</div>
            <a class="nav-link" href="{{ route('admin.patient_messages') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-comment"></i></div>
                Patient Messages
                <span id="sidebarUnreadMessagesBadge" class="badge bg-secondary ms-2">0</span>
            </a>
            <a class="nav-link" href="{{ route('admin.patient_information') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                User Information
            </a>
            <a class="nav-link" href="{{ route('admin.appointments') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-calendar"></i></div>
                Upcoming Appointments
            </a>
            <a class="nav-link" href="{{ route('admin.upcoming_appointments') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                Pending Appointments
                <span id="sidebarPendingApptBadge" class="badge bg-secondary ms-2">0</span>
            </a>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                Edit Availability
            </a>
            <a class="nav-link" href="{{ route('admin.inventory_admin') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                Inventory
            </a>
            <a class="nav-link" href="{{ route('admin.procedure_prices') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                Dental Prices
            </a>
            <a class="nav-link" href="{{ route('admin.teeth_layout') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-teeth"></i></div>
                Teeth Layout Management
            </a>

            <div class="sb-sidenav-menu-heading">Interface</div>
            <a class="nav-link" href="{{ url('/profile') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user-edit"></i></div>
                Edit Profile
            </a>

            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="{{ url('admin/reviews') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                Reviews
            </a>
        </div>
    </div>

    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ __(auth()->user()->name) }}
    </div>
</nav>

<script>
// Update unread messages badge in sidebar
function updateSidebarUnreadMessagesCount() {
    $.ajax({
        url: "{{ url('/admin/unread-messages-count') }}",
        method: "GET",
        success: function(response) {
            let badge = $("#sidebarUnreadMessagesBadge");
            if (response.count > 0) {
                badge.text(response.count).removeClass("bg-secondary").addClass("bg-danger");
            } else {
                badge.text("0").removeClass("bg-danger").addClass("bg-secondary");
            }
        }
    });
}

// Update pending appointments badge in sidebar
function updateSidebarPendingCount() {
    $.ajax({
        url: "{{ route('notifications.pending-count') }}",
        method: "GET",
        success: function(response) {
            let count = response.pendingCount;
            let badge = $("#sidebarPendingApptBadge");
            if (count > 0) {
                badge.text(count).removeClass("bg-secondary").addClass("bg-danger");
            } else {
                badge.text("0").removeClass("bg-danger").addClass("bg-secondary");
            }
        }
    });
}

$(document).ready(function() {
    // Initialize badges
    updateSidebarUnreadMessagesCount();
    updateSidebarPendingCount();

    // Refresh counts every 30 seconds
    setInterval(function() {
        updateSidebarUnreadMessagesCount();
        updateSidebarPendingCount();
    }, 30000);
});
</script>

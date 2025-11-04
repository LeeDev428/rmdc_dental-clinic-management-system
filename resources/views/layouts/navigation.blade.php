<nav x-data="{ open: false, notificationsOpen: false, currentPage: 0 }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center" style="width: 100%;">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo />
                    </a>
                </div>
                
                <!-- Modern Carousel Navigation -->
                <div class="hidden sm:flex items-center justify-center flex-1">
                    <!-- Previous Button -->
                    <button @click="currentPage = Math.max(0, currentPage - 1)" 
                            :class="currentPage === 0 ? 'opacity-30' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                            :style="currentPage === 0 ? 'cursor: not-allowed;' : 'cursor: pointer;'"
                            class="p-2 rounded-full transition-all flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Navigation Items Container - Shows 3 items at a time -->
                    <div class="overflow-hidden mx-4" style="width: 600px;">
                        <div class="flex transition-transform duration-300 ease-in-out" 
                             :style="'transform: translateX(-' + (currentPage * 100) + '%)'">
                            
                            <!-- Page 1: Dashboard, Dental Records, Health Progress -->
                            <div class="min-w-full flex items-center justify-center gap-3">
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-home mr-2"></i>Dashboard
                                </x-nav-link>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <x-nav-link :href="route('patient.dental_records')" :active="request()->routeIs('patient.dental_records')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-file-medical mr-2"></i>Dental Records
                                </x-nav-link>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <x-nav-link :href="route('health.progress')" :active="request()->routeIs('health.progress')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-chart-line mr-2"></i>Health Progress
                                </x-nav-link>
                            </div>
                            
                            <!-- Page 2: Appointments, Appointment History, Profile -->
                            <div class="min-w-full flex items-center justify-center gap-3">
                                <x-nav-link :href="route('appointments')" :active="request()->routeIs('appointments')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-calendar-plus mr-2"></i>Appointment
                                </x-nav-link>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <x-nav-link :href="route('appointment.history')" :active="request()->routeIs('appointment.history')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-history mr-2"></i>History
                                </x-nav-link>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </x-nav-link>
                            </div>
                            
                            <!-- Page 3: Services, Ask Lee AI, Settings -->
                            <div class="min-w-full flex items-center justify-center gap-3">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-300 px-4 py-2 whitespace-nowrap cursor-pointer">
                                    <i class="fas fa-concierge-bell mr-2"></i>Services
                                </div>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-300 px-4 py-2 whitespace-nowrap cursor-pointer">
                                    <i class="fas fa-robot mr-2"></i>Ask Lee AI
                                </div>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <x-nav-link :href="route('usersettings')" :active="request()->routeIs('usersettings')" 
                                            class="text-sm font-medium px-4 py-2 whitespace-nowrap">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </x-nav-link>
                            </div>
                            
                            <!-- Page 4: Log Out -->
                            <div class="min-w-full flex items-center justify-center gap-3">
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-gray-600 dark:text-gray-300 px-4 py-2 hover:text-gray-900 dark:hover:text-white whitespace-nowrap">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Next Button -->
                    <button @click="currentPage = Math.min(3, currentPage + 1)" 
                            :class="currentPage === 3 ? 'opacity-30' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                            :style="currentPage === 3 ? 'cursor: not-allowed;' : 'cursor: pointer;'"
                            class="p-2 rounded-full transition-all flex-shrink-0 -ml-2">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Notification Message (Right Corner) -->
            <div class="flex items-center space-x-1">
                <button onclick="markMessagesAsRead()" class="relative p-3 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition-all duration-300 ease-in-out shadow-lg hover:shadow-2xl transform hover:scale-105">
                    <!-- Message Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-600 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2-2 0 012-2zM3 8l9 6 9-6" />
                    </svg>

                    <!-- Notification Badge -->
                    <span id="message-count" class="absolute top-0 right-0 rounded-full bg-red-500 text-white text-xs font-semibold px-2 py-1 notification-count animate-pulse hidden">0</span>
                </button>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    function fetchUnreadMessagesCount() {
                        $.ajax({
                            url: "/unread-messages-count",
                            type: "GET",
                            dataType: "json",
                            success: function (response) {
                                let count = response.count;
                                let badge = $("#message-count");

                                if (count > 0) {
                                    badge.text(count).removeClass("hidden");
                                } else {
                                    badge.addClass("hidden");
                                }
                            },
                            error: function (xhr) {
                                console.error("Error fetching unread count:", xhr);
                            }
                        });
                    }

                    function markMessagesAsRead() {
                        $.ajax({
                            url: "/mark-messages-as-read",
                            type: "POST",
                            data: { _token: "{{ csrf_token() }}" },
                            success: function () {
                                $("#message-count").addClass("hidden");
                                window.location.href = "{{ route('messages.index') }}";
                            },
                            error: function (xhr) {
                                console.error("Error marking messages as read:", xhr);
                            }
                        });
                    }

                    $(document).ready(fetchUnreadMessagesCount);
                    setInterval(fetchUnreadMessagesCount, 5000);
                </script>



                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <!-- Notification Bell Icon -->
                <div class="relative">
                    <button @click="notificationsOpen = !notificationsOpen" class="relative p-3 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none transition-all duration-300 ease-in-out shadow-lg hover:shadow-2xl transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-600 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22c1.5 0 3-1.5 3-3H9c0 1.5 1.5 3 3 3zM18 16V7c0-3.3-2.7-6-6-6s-6 2.7-6 6v9l-1 1v1h14v-1l-1-1z" />
                        </svg>
                    </button>
                    @php
                        $unreadNotifications = App\Models\Notification::where('user_id', Auth::id())
                            ->where('status', 'unread')
                            ->latest()
                            ->get();

                        $allNotifications = App\Models\Notification::where('user_id', Auth::id())
                            ->latest()
                            ->get();
                    @endphp
             @if ($unreadNotifications->count() > 0)
             <span id="notification-badge" class="absolute top-0 right-0 rounded-full bg-red-500 text-white text-xs font-semibold px-2 py-1 notification-count animate-pulse">
                 {{ $unreadNotifications->count() > 10 ? '10+' : $unreadNotifications->count() }}
             </span>
         @else
             <span id="notification-badge" class="absolute top-0 right-0 rounded-full bg-red-500 text-white text-xs font-semibold px-2 py-1 notification-count animate-pulse" style="display: none;">
                 0
             </span>
         @endif

         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script>
             function updateUnreadNotifications() {
                 $.ajax({
                     url: "{{ route('notifications.unread-count') }}", // Route to get unread count
                     method: "GET",
                     success: function(response) {
                         let count = response.unreadCount;
                         let badge = $("#notification-badge");

                         if (count > 0) {
                             badge.text(count > 10 ? "10+" : count).show(); // Apply "10+" logic
                         } else {
                             badge.hide(); // Hide if 0
                         }
                     },
                     error: function(xhr) {
                         console.log("Error fetching unread notifications:", xhr);
                     }
                 });
             }

             // Update every 10 seconds
             setInterval(updateUnreadNotifications, 10000);

             // Initial call when page loads
             $(document).ready(function() {
                 updateUnreadNotifications();
             });
         </script>

                    <!-- Notification Dropdown -->
                    <div
                    x-show="notificationsOpen"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 border rounded-lg shadow-lg max-h-64 overflow-y-auto z-10"
                    x-cloak
                >
                    <div class="p-2 border-b bg-gray-100 dark:bg-gray-700 flex justify-between items-center dark:text-white">
                        <b style="font-size: 16px;">Notifications</b>
                        <button id="mark-as-read" class="text-sm text-blue-500 hover:text-blue-700 dark:text-blue-300">Mark all as read</button>
                    </div>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle Mark all as Read
        $("#mark-as-read").click(function () {
            $.ajax({
                url: "{{ route('notifications.mark-as-read') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        // Reset notification count
                        $("#notification-badge").hide();
                        // Optionally remove all notifications from the dropdown
                        $("#notification-list").html("<p class='p-2 text-center text-gray-500'>No notifications</p>");
                    }
                },
                error: function (xhr) {
                    console.log("Error marking notifications as read:", xhr);
                }
            });
        });
    });
</script>

                    <ul id="notification-list" class="font-poppins text-gray-700 dark:text-gray-300 text-sm p-3">
                        <p class="text-center italic text-gray-600">Loading Notifications...</p>
                    </ul>

                    <!-- Google Fonts -->
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

                    <!-- Tailwind CSS or Custom Styles -->
                    <style>
                        body {
                            font-family: 'Poppins', sans-serif;
                        }
                    </style>

                </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    function fetchNotifications() {
    $.ajax({
        url: "{{ route('notifications.fetch') }}",
        method: "GET",
        success: function(response) {
            let dropdown = $("#notification-list");
            dropdown.empty();

            if (response.notifications.length > 0) {
                response.notifications.forEach(notification => {
                    let item = `
                        <li class="p-2 border-b notification-item text-sm max-w-full line-clamp-5 dark:text-white">
                            <div class="font-sm text-black dark:text-white">${notification.message}</div>
                            <div class="text-xs text-gray-500 mt-1 dark:text-gray-400">${formatDate(notification.created_at)}</div>
                            <div class="text-xs text-gray-500 mt-1 dark:text-gray-400">${timeAgo(notification.created_at)}</div>
                        </li>`;
                    dropdown.append(item);
                });
            } else {
                dropdown.html("<p class='p-2 text-center text-gray-500'>No notifications</p>");
            }
        },
        error: function(xhr) {
            console.error("Error fetching notifications:", xhr);
        }
    });
}


function updateUnreadNotifications() {
    $.ajax({
        url: "{{ route('notifications.fetch') }}", // Fetch both notifications & count
        method: "GET",
        success: function(response) {
            let count = response.unreadCount; // Use the correct count
            let badge = $("#notification-badge");

            if (count > 10) {
                badge.text("10+").show();
            } else if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        },
        error: function(xhr) {
            console.log("Error fetching unread notifications:", xhr);
        }
    });
}

function formatDate(dateString) {
    let date = new Date(dateString);
    return date.toLocaleDateString("en-US") + " " + date.toLocaleTimeString("en-US", { hour: '2-digit', minute: '2-digit' });
}


    function timeAgo(dateString) {
        let date = new Date(dateString);
        let seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 60);

        if (interval < 1) return "Just now";
        if (interval < 60) return interval + " minutes ago";
        interval = Math.floor(interval / 60);
        if (interval < 24) return interval + " hours ago";
        interval = Math.floor(interval / 24);
        if (interval < 30) return interval + " days ago";
        interval = Math.floor(interval / 30);
        return interval + " months ago";
    }

    // Auto-update every 10 seconds
    setInterval(function () {
        updateUnreadNotifications();
        fetchNotifications();
    }, 10000);

    // Initial load
    updateUnreadNotifications();
    fetchNotifications();
});
</script>



<!-- Profile Dropdown -->
<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <!-- Profile Button -->
    <button class="inline-flex items-center px-7 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-white bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150" @click="open = !open">
        <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full">
        <div class="ms-2 hidden sm:block dark:text-white">{{ Auth::user()->name }}</div>
        <div class="ms-1">
            <svg class="fill-current h-4 w-4 dark:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg ring-1 ring-black ring-opacity-5 z-10">
  <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 dark:text-white">{{ __('Profile') }}</x-dropdown-link> 
        <x-dropdown-link :href="route('appointments')" class="text-gray-700 dark:text-white">{{ __('Appointment') }}</x-dropdown-link>
        <x-dropdown-link :href="route('appointment.history')" class="text-gray-700 dark:text-white">{{ __('Appointment History') }}</x-dropdown-link>
        <x-dropdown-link :href="route('patient.dental_records')" class="text-gray-700 dark:text-white">{{ __('Dental Records') }}</x-dropdown-link>
        <x-dropdown-link :href="route('health.progress')" class="text-gray-700 dark:text-white">{{ __('Health Progress') }}</x-dropdown-link>
        <x-dropdown-link :href="route('dashboard')" class="text-gray-700 dark:text-white">{{ __('Dashboard') }}</x-dropdown-link>
        <x-dropdown-link class="text-gray-700 dark:text-white">{{ __('Ask Lee AI?') }}</x-dropdown-link>
        <x-dropdown-link class="text-gray-700 dark:text-white">{{ __('Services') }}</x-dropdown-link>
        <x-dropdown-link :href="route('usersettings')" class="text-gray-700 dark:text-white">{{ __('Settings') }}</x-dropdown-link>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-700 dark:text-white">{{ __('Log Out') }}</x-dropdown-link>
        </form>
    </div>
</div>


            </div>
        </div>
    </div>
</nav>

@vite('resources/js/app.js')
<script>
    Echo.channel('appointments')
        .listen('AppointmentStatusChanged', (e) => {
            fetchNotifications();
        });

    const fetchNotifications = async () => {
        try {
            const response = await fetch('/get-unread-count');
            const data = await response.json();
            const notificationCount = document.querySelector('.notification-count');
            if (data.unreadCount > 0) {
                notificationCount.textContent = data.unreadCount;
                notificationCount.style.display = "block";
            } else {
                notificationCount.style.display = "none";
            }
        } catch (error) {
            console.error("Error fetching notifications:", error);
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

    document.getElementById('mark-as-read').addEventListener('click', markAsRead);
</script>

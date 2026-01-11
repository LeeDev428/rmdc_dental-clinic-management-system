<div class="text-gray-900 dark:text-gray-100">
    <h3 class="text-xl font-bold mb-4">Notification History</h3>
    
    <!-- Search and Filter Section -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
        <form id="notificationFilterForm" method="GET" action="{{ url()->current() }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium mb-2">Search</label>
                    <input type="text" name="notification_search" id="notification_search" 
                           value="{{ request('notification_search') }}" 
                           placeholder="Search notifications..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Read Status Filter -->
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select name="notification_status" id="notification_status" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">All Notifications</option>
                        <option value="unread" {{ request('notification_status') == 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('notification_status') == 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium mb-2">From Date</label>
                    <input type="date" name="notification_from" id="notification_from" 
                           value="{{ request('notification_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium mb-2">To Date</label>
                    <input type="date" name="notification_to" id="notification_to" 
                           value="{{ request('notification_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
            </div>
            
            <div class="mt-4 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
                <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
    
    @php
        $query = auth()->user()->notifications();
        
        // Apply search filter
        if (request('notification_search')) {
            $search = request('notification_search');
            $query->where(function($q) use ($search) {
                $q->whereRaw('JSON_EXTRACT(data, "$.title") LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('JSON_EXTRACT(data, "$.message") LIKE ?', ["%{$search}%"]);
            });
        }
        
        // Apply read status filter
        if (request('notification_status') == 'unread') {
            $query->whereNull('read_at');
        } elseif (request('notification_status') == 'read') {
            $query->whereNotNull('read_at');
        }
        
        // Apply date range filter
        if (request('notification_from')) {
            $query->whereDate('created_at', '>=', request('notification_from'));
        }
        if (request('notification_to')) {
            $query->whereDate('created_at', '<=', request('notification_to'));
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
    @endphp
    
    @if($notifications->isNotEmpty())
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 
                            {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-l-blue-500' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 dark:text-gray-100">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $notification->data['message'] ?? 'No message available' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notification->read_at)
                            <span class="ml-3 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">New</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $notifications->appends(request()->query())->links('vendor.pagination.compact-bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400">No notifications yet.</p>
        </div>
    @endif
</div>

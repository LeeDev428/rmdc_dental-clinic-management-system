<div class="text-gray-900 dark:text-gray-100">
    <h3 class="text-xl font-bold mb-4">Notification History</h3>
    
    <!-- Search and Filter Section -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
        <form id="notificationFilterForm" method="GET" action="{{ url()->current() }}">
            <input type="hidden" name="tab" value="notification">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium mb-2">Search</label>
                    <input type="text" name="notification_search" id="notification_search" 
                           value="{{ request('notification_search') }}" 
                           placeholder="Search notifications, emails, messages..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium mb-2">Type</label>
                    <select name="notification_type" id="notification_type" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">All Types</option>
                        <option value="notification" {{ request('notification_type') == 'notification' ? 'selected' : '' }}>System Notifications</option>
                        <option value="email" {{ request('notification_type') == 'email' ? 'selected' : '' }}>Emails</option>
                        <option value="message" {{ request('notification_type') == 'message' ? 'selected' : '' }}>Messages</option>
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
                <a href="{{ url()->current() }}?tab=notification" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
    
    @php
        use Illuminate\Support\Facades\Cache;
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;
        
        // Cache key for this user's comprehensive notifications
        $cacheKey = 'user_comprehensive_notifications_' . auth()->id() . '_v2';
        $cacheDuration = 300; // 5 minutes cache (reduced for fresher data)
        
        // Clear cache for fresh data (optional, remove in production)
        // Cache::forget($cacheKey);
        
        // Get all notifications, emails, and messages
        $allItems = Cache::remember($cacheKey, $cacheDuration, function() {
            $items = collect();
            
            // 1. System Notifications (from notifications table - Laravel's notification system)
            try {
                $systemNotifications = auth()->user()->notifications()
                    ->where('created_at', '>=', Carbon::now()->subWeeks(7))
                    ->get()
                    ->map(function($notification) {
                        return [
                            'id' => $notification->id,
                            'type' => 'notification',
                            'type_label' => 'System Notification',
                            'icon' => 'bell',
                            'icon_color' => 'text-blue-500',
                            'title' => $notification->data['title'] ?? 'Notification',
                            'message' => $notification->data['message'] ?? 'No message available',
                            'created_at' => $notification->created_at,
                            'read_at' => $notification->read_at,
                            'is_new' => $notification->created_at->gt(Carbon::now()->subHours(24)),
                            'data' => $notification->data
                        ];
                    });
                $items = $items->merge($systemNotifications);
            } catch (\Exception $e) {
                \Log::warning('Could not fetch system notifications: ' . $e->getMessage());
            }
            
            // 2. Custom Notifications (from our notifications table)
            try {
                if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'user_id')) {
                    $customNotifications = DB::table('notifications')
                        ->where('user_id', auth()->id())
                        ->where('created_at', '>=', Carbon::now()->subWeeks(7))
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function($notification) {
                            return [
                                'id' => $notification->id,
                                'type' => 'notification',
                                'type_label' => 'Notification',
                                'icon' => 'bell',
                                'icon_color' => 'text-purple-500',
                                'title' => 'Notification',
                                'message' => $notification->message ?? '',
                                'created_at' => Carbon::parse($notification->created_at),
                                'read_at' => isset($notification->status) && $notification->status === 'read' ? Carbon::now() : null,
                                'is_new' => Carbon::parse($notification->created_at)->gt(Carbon::now()->subHours(24)),
                                'data' => []
                            ];
                        });
                    $items = $items->merge($customNotifications);
                }
            } catch (\Exception $e) {
                \Log::warning('Could not fetch custom notifications: ' . $e->getMessage());
            }
            
            // 3. Messages (from messages table - only if using MySQL, not MongoDB)
            try {
                // Check if messages table exists and is using MySQL (not MongoDB)
                $connection = config('database.default');
                if ($connection !== 'mongodb' && Schema::hasTable('messages')) {
                    $messages = DB::table('messages')
                        ->where('user_id', auth()->id())
                        ->where('is_admin', true) // Only messages from admin
                        ->where('created_at', '>=', Carbon::now()->subWeeks(7))
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function($message) {
                            return [
                                'id' => $message->id,
                                'type' => 'message',
                                'type_label' => 'Admin Message',
                                'icon' => 'comment',
                                'icon_color' => 'text-green-500',
                                'title' => 'Message from Admin',
                                'message' => $message->message ?? $message->content ?? '',
                                'created_at' => Carbon::parse($message->created_at),
                                'read_at' => isset($message->status) && $message->status === 'read' ? Carbon::now() : null,
                                'is_new' => Carbon::parse($message->created_at)->gt(Carbon::now()->subHours(24)),
                                'data' => []
                            ];
                        });
                    $items = $items->merge($messages);
                }
            } catch (\Exception $e) {
                \Log::warning('Could not fetch messages: ' . $e->getMessage());
            }
            
            return $items->sortByDesc('created_at')->values()->all();
        });
        
        // Convert to collection for filtering
        $allItems = collect($allItems);
        
        // Apply search filter
        if (request('notification_search')) {
            $search = strtolower(request('notification_search'));
            $allItems = $allItems->filter(function($item) use ($search) {
                return str_contains(strtolower($item['title']), $search) ||
                       str_contains(strtolower($item['message']), $search) ||
                       str_contains(strtolower($item['type_label']), $search);
            });
        }
        
        // Apply type filter
        if (request('notification_type')) {
            $allItems = $allItems->where('type', request('notification_type'));
        }
        
        // Apply date range filter
        if (request('notification_from')) {
            $fromDate = Carbon::parse(request('notification_from'))->startOfDay();
            $allItems = $allItems->filter(function($item) use ($fromDate) {
                return Carbon::parse($item['created_at'])->gte($fromDate);
            });
        }
        if (request('notification_to')) {
            $toDate = Carbon::parse(request('notification_to'))->endOfDay();
            $allItems = $allItems->filter(function($item) use ($toDate) {
                return Carbon::parse($item['created_at'])->lte($toDate);
            });
        }
        
        // Manual pagination
        $perPage = 15;
        $currentPage = request('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $allItems->slice($offset, $perPage);
        $totalItems = $allItems->count();
        $lastPage = ceil($totalItems / $perPage);
    @endphp
    
    @if($paginatedItems->isNotEmpty())
        <div class="space-y-3">
            @foreach($paginatedItems as $item)
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 
                            {{ $item['is_new'] ? 'border-l-4 border-l-blue-500' : '' }} 
                            {{ !$item['read_at'] && $item['type'] === 'notification' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-3 flex-1">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-{{ $item['icon'] }} {{ $item['icon_color'] }} text-xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-gray-800 dark:text-gray-100">
                                        {{ $item['title'] }}
                                    </p>
                                    <span class="px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded">
                                        {{ $item['type_label'] }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $item['message'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ Carbon::parse($item['created_at'])->diffForHumans() }}
                                    <span class="ml-2 text-gray-400">{{ Carbon::parse($item['created_at'])->format('M d, Y h:i A') }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="flex flex-col gap-1 items-end ml-3">
                            @if($item['is_new'])
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded font-semibold">
                                    <i class="fas fa-star mr-1"></i>New
                                </span>
                            @endif
                            @if(!$item['read_at'] && $item['type'] === 'notification')
                                {{-- <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded">
                                    Unread
                                </span> --}}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Manual Pagination -->
        @if($lastPage > 1)
            <div class="mt-6">
                <nav class="flex justify-center">
                    <ul class="inline-flex items-center -space-x-px">
                        <!-- Previous Page -->
                        <li>
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['page' => max(1, $currentPage - 1), 'tab' => 'notification'])) }}" 
                               class="block px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        
                        <!-- Page Numbers -->
                        @for($i = 1; $i <= $lastPage; $i++)
                            @if($i == 1 || $i == $lastPage || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
                                <li>
                                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['page' => $i, 'tab' => 'notification'])) }}" 
                                       class="px-3 py-2 leading-tight {{ $i == $currentPage ? 'text-blue-600 bg-blue-50 border border-blue-300 dark:bg-gray-700 dark:text-white' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @elseif($i == $currentPage - 3 || $i == $currentPage + 3)
                                <li>
                                    <span class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span>
                                </li>
                            @endif
                        @endfor
                        
                        <!-- Next Page -->
                        <li>
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['page' => min($lastPage, $currentPage + 1), 'tab' => 'notification'])) }}" 
                               class="block px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <p class="text-center mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ $offset + 1 }} to {{ min($offset + $perPage, $totalItems) }} of {{ $totalItems }} notifications
                </p>
            </div>
        @endif
        
        <!-- Summary Card -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-gray-700 rounded-lg border border-blue-200 dark:border-gray-600">
            <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Notification Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Total</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $totalItems }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">New (24hrs)</p>
                    <p class="text-xl font-bold text-blue-600">{{ $allItems->where('is_new', true)->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Messages</p>
                    <p class="text-xl font-bold text-green-600">{{ $allItems->where('type', 'message')->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">System</p>
                    <p class="text-xl font-bold text-purple-600">{{ $allItems->where('type', 'notification')->count() }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400 text-lg">No notifications found.</p>
            @if(request()->hasAny(['notification_search', 'notification_type', 'notification_from', 'notification_to']))
                <p class="text-sm text-gray-500 mt-2">Try adjusting your filters.</p>
            @endif
        </div>
    @endif
</div>

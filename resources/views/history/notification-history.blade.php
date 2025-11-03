<div class="text-gray-900 dark:text-gray-100">
    <h3 class="text-xl font-bold mb-4">Notification History</h3>
    
    @php
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
    @endphp
    
    @if($notifications->count() > 0)
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
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400">No notifications yet.</p>
        </div>
    @endif
</div>

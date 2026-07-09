<div class="text-gray-900 dark:text-gray-100">
    <h3 class="text-xl font-bold mb-4">Appointment History</h3>
    
    <!-- Search and Filter Section -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
        <form id="appointmentFilterForm" method="GET" action="{{ url()->current() }}">
            <input type="hidden" name="tab" value="appointment">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium mb-2">Search</label>
                    <input type="text" name="appointment_search" id="appointment_search" 
                           value="{{ request('appointment_search') }}" 
                           placeholder="Title, Procedure..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select name="appointment_status" id="appointment_status" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('appointment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('appointment_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="declined" {{ request('appointment_status') == 'declined' ? 'selected' : '' }}>Declined</option>
                        <option value="completed" {{ request('appointment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium mb-2">From Date</label>
                    <input type="date" name="appointment_from" id="appointment_from" 
                           value="{{ request('appointment_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium mb-2">To Date</label>
                    <input type="date" name="appointment_to" id="appointment_to" 
                           value="{{ request('appointment_to') }}"
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
        $query = App\Models\Appointment::where('user_id', auth()->id());
        
        // Apply search filter
        if (request('appointment_search')) {
            $search = request('appointment_search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('procedure', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if (request('appointment_status')) {
            $query->where('status', request('appointment_status'));
        }
        
        // Apply date range filter
        if (request('appointment_from')) {
            $query->whereDate('start', '>=', request('appointment_from'));
        }
        if (request('appointment_to')) {
            $query->whereDate('start', '<=', request('appointment_to'));
        }
        
        $appointments = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
    @endphp
    
    @if($appointments->count() > 0)
        <div class="space-y-4">
            @foreach($appointments as $appointment)
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-5 bg-gray-50 dark:bg-gray-700 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ $appointment->title }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <i class="fas fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($appointment->start)->format('F j, Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-md
                            @if($appointment->status == 'pending')
                                bg-yellow-100 text-yellow-800
                            @elseif($appointment->status == 'accepted')
                                bg-green-100 text-green-800
                            @elseif($appointment->status == 'declined')
                                bg-red-100 text-red-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Procedure:</strong> {{ $appointment->procedure }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                <strong>Time:</strong> {{ $appointment->time }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                <strong>Duration:</strong> {{ $appointment->duration }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Start:</strong> {{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                <strong>End:</strong> {{ \Carbon\Carbon::parse($appointment->end)->format('h:i A') }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                <strong>Created:</strong> {{ $appointment->created_at->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                    
                    @if($appointment->total_price)
                        <div class="mt-3 pt-3 border-t border-gray-300 dark:border-gray-600">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Payment Arrangement</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        <strong>Total:</strong> ₱{{ number_format($appointment->total_price, 2) }}
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        <strong>Payment Mode:</strong> Physical at clinic
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        <strong>Collection Status:</strong> To be settled at visit
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        <strong>Reference:</strong> N/A (physical payment)
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($appointment->notes)
                        <div class="mt-3 pt-3 border-t border-gray-300 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Notes:</strong> {{ $appointment->notes }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $appointments->appends(request()->query())->links('vendor.pagination.compact-bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400 text-lg">No appointment history found.</p>
        </div>
    @endif
</div>

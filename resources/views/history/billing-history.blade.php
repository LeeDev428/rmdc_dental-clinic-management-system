<div class="text-gray-900 dark:text-gray-100">
    <h3 class="text-xl font-bold mb-4">Billing History</h3>
    
    <!-- Search and Filter Section -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
        <form id="billingFilterForm" method="GET" action="{{ url()->current() }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium mb-2">Search</label>
                    <input type="text" name="billing_search" id="billing_search" 
                           value="{{ request('billing_search') }}" 
                           placeholder="Invoice #, Procedure..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select name="billing_status" id="billing_status" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('billing_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('billing_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="declined" {{ request('billing_status') == 'declined' ? 'selected' : '' }}>Declined</option>
                        <option value="completed" {{ request('billing_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium mb-2">From Date</label>
                    <input type="date" name="billing_from" id="billing_from" 
                           value="{{ request('billing_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium mb-2">To Date</label>
                    <input type="date" name="billing_to" id="billing_to" 
                           value="{{ request('billing_to') }}"
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
        // Get appointments with billing information with filters
        $query = App\Models\Appointment::where('user_id', auth()->id())
            ->whereNotNull('procedure');
            
        // Apply search filter
        if (request('billing_search')) {
            $search = request('billing_search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('procedure', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if (request('billing_status')) {
            $query->where('status', request('billing_status'));
        }
        
        // Apply date range filter
        if (request('billing_from')) {
            $query->whereDate('created_at', '>=', request('billing_from'));
        }
        if (request('billing_to')) {
            $query->whereDate('created_at', '<=', request('billing_to'));
        }
            
        $billingHistory = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
    @endphp
    
    @if($billingHistory->count() > 0)
        <div class="space-y-4">
            @foreach($billingHistory as $billing)
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-5 bg-gray-50 dark:bg-gray-700">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                Invoice #{{ $billing->id }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <i class="fas fa-calendar mr-2"></i>{{ $billing->created_at->format('F j, Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-md
                            @if($billing->status == 'pending')
                                bg-yellow-100 text-yellow-800
                            @elseif($billing->status == 'accepted')
                                bg-green-100 text-green-800
                            @elseif($billing->status == 'declined')
                                bg-red-100 text-red-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($billing->status) }}
                        </span>
                    </div>
                    
                    <div class="border-t border-gray-300 dark:border-gray-600 pt-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <strong>Service:</strong> {{ $billing->procedure }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">
                                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($billing->start)->format('M j, Y') }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">
                                    <strong>Time:</strong> {{ $billing->time }}
                                </p>
                            </div>
                            <div class="text-right">
                                @php
                                    // Use total_price from appointment if available, otherwise get from procedure prices
                                    $amount = $billing->total_price ?? 0;
                                    if (!$amount) {
                                        $procedurePrice = App\Models\ProcedurePrice::where('procedure_name', $billing->procedure)->first();
                                        $amount = $procedurePrice ? $procedurePrice->price : 0;
                                    }
                                @endphp
                                <p class="text-gray-600 dark:text-gray-400">
                                    <strong>Total Amount:</strong>
                                </p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                                    ₱{{ number_format($amount, 2) }}
                                </p>
                                @if($billing->down_payment)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Down Payment: ₱{{ number_format($billing->down_payment, 2) }}
                                    </p>
                                @endif
                                @if($billing->payment_status == 'paid')
                                    <span class="inline-block mt-2 text-xs text-green-600 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>Paid
                                    </span>
                                @elseif($billing->payment_status == 'pending')
                                    <span class="inline-block mt-2 text-xs text-yellow-600 dark:text-yellow-400">
                                        <i class="fas fa-clock mr-1"></i>Pending Payment
                                    </span>
                                @else
                                    <span class="inline-block mt-2 text-xs text-red-600 dark:text-red-400">
                                        <i class="fas fa-times-circle mr-1"></i>Unpaid
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($billing->payment_method || $billing->payment_reference)
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                                    @if($billing->payment_method)
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <strong>Payment Method:</strong> {{ strtoupper($billing->payment_method) }}
                                        </p>
                                    @endif
                                    @if($billing->payment_reference)
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <strong>Reference:</strong> {{ $billing->payment_reference }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-300 dark:border-gray-600 flex justify-between items-center">
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            Issued: {{ $billing->created_at->format('M j, Y h:i A') }}
                        </p>
                        <a href="{{ route('invoice.download', $billing->id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center"
                           target="_blank">
                            <i class="fas fa-download mr-1"></i>Download Invoice
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $billingHistory->links() }}
        </div>
        
        <!-- Summary Card -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-gray-700 rounded-lg border border-blue-200 dark:border-gray-600">
            <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Billing Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Total Invoices</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $billingHistory->total() }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Processed</p>
                    <p class="text-xl font-bold text-green-600">
                        {{ App\Models\Appointment::where('user_id', auth()->id())->where('status', 'accepted')->count() }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-xl font-bold text-yellow-600">
                        {{ App\Models\Appointment::where('user_id', auth()->id())->where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-file-invoice text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400 text-lg">No billing history found.</p>
        </div>
    @endif
</div>

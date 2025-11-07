<div class="text-gray-900 dark:text-gray-100">
    <h3 class="text-xl font-bold mb-4">Billing History</h3>
    
    @php
        // Get appointments with billing information
        $billingHistory = App\Models\Appointment::where('user_id', auth()->id())
            ->whereNotNull('procedure')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i>Download Invoice
                        </button>
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

<x-app-layout>
    @section('title', 'Appointment History')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-6">My Appointment History</h3>
                    
                    @if($appointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($appointments as $appointment)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-6 bg-gray-50 dark:bg-gray-700 hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
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
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Procedure:</strong> {{ $appointment->procedure }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                <strong>Time:</strong> {{ $appointment->time }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                <strong>Duration:</strong> {{ $appointment->duration }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Start:</strong> {{ \Carbon\Carbon::parse($appointment->start)->format('h:i A') }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                <strong>End:</strong> {{ \Carbon\Carbon::parse($appointment->end)->format('h:i A') }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                <strong>Created:</strong> {{ $appointment->created_at->format('M j, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($appointment->notes)
                                        <div class="mt-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Notes:</strong> {{ $appointment->notes }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400 text-lg">No appointment history found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

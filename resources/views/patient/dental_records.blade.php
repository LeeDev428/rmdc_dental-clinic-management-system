<x-app-layout>
    <div class="py-12" style="background-color: #f9fafb;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">My Dental Records</h2>
                </div>

                @if($records->isEmpty())
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 text-center">
                        <i class="fas fa-info-circle text-blue-500 text-4xl mb-3"></i>
                        <p class="text-blue-700 dark:text-blue-300">No dental records found. Your dentist will add records after your visits.</p>
                    </div>
                @else
                    <div class="grid gap-6">
                        @foreach($records as $record)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                            {{ $record->visit_date->format('F d, Y') }}
                                        </h3>
                                        @if($record->dentist)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                <i class="fas fa-user-md mr-1"></i> Dr. {{ $record->dentist->name }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('patient.dental_record.show', $record->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-eye mr-2"></i> View Details
                                    </a>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4 text-sm">
                                    @if($record->chief_complaint)
                                        <div>
                                            <strong class="text-gray-700 dark:text-gray-300">Chief Complaint:</strong>
                                            <p class="text-gray-600 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($record->chief_complaint, 60) }}</p>
                                        </div>
                                    @endif

                                    @if($record->diagnosis)
                                        <div>
                                            <strong class="text-gray-700 dark:text-gray-300">Diagnosis:</strong>
                                            <p class="text-gray-600 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($record->diagnosis, 60) }}</p>
                                        </div>
                                    @endif

                                    @if($record->treatment_performed)
                                        <div>
                                            <strong class="text-gray-700 dark:text-gray-300">Treatment:</strong>
                                            <p class="text-gray-600 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($record->treatment_performed, 60) }}</p>
                                        </div>
                                    @endif

                                    @if($record->next_visit)
                                        <div>
                                            <strong class="text-gray-700 dark:text-gray-300">Next Visit:</strong>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $record->next_visit->format('M d, Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>

<x-app-layout>
    <div class="py-12" style="background-color: #f9fafb;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="mb-6">
                    <a href="{{ route('patient.dental_records') }}" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        <i class="fas fa-arrow-left mr-2"></i> Back to All Records
                    </a>
                </div>

                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white mb-6">
                    <h2 class="text-3xl font-bold mb-2">
                        <i class="fas fa-file-medical mr-3"></i> Dental Record
                    </h2>
                    <p class="text-lg">{{ $record->visit_date->format('F d, Y') }}</p>
                    @if($record->dentist)
                        <p class="text-sm opacity-90 mt-2">
                            <i class="fas fa-user-md mr-1"></i> Attended by: Dr. {{ $record->dentist->name }}
                        </p>
                    @endif
                </div>

                <!-- Visit Information -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-calendar-check text-blue-500 mr-2"></i> Visit Information
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <strong class="block text-gray-700 dark:text-gray-300 mb-1">Chief Complaint:</strong>
                            <p class="text-gray-600 dark:text-gray-400">{{ $record->chief_complaint ?: 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Medical History -->
                @if($record->medical_history || $record->current_medications || $record->allergies || $record->blood_pressure)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-notes-medical text-green-500 mr-2"></i> Medical History
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($record->medical_history)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Medical History:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->medical_history }}</p>
                                </div>
                            @endif
                            @if($record->current_medications)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Current Medications:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->current_medications }}</p>
                                </div>
                            @endif
                            @if($record->allergies)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Allergies:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->allergies }}</p>
                                </div>
                            @endif
                            @if($record->blood_pressure)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Blood Pressure:</strong>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $record->blood_pressure }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Dental Examination -->
                @if($record->oral_examination || $record->gum_condition || $record->tooth_condition || $record->xray_findings)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-teeth text-purple-500 mr-2"></i> Dental Examination
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($record->oral_examination)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Oral Examination:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->oral_examination }}</p>
                                </div>
                            @endif
                            @if($record->gum_condition)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Gum Condition:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->gum_condition }}</p>
                                </div>
                            @endif
                            @if($record->tooth_condition)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Tooth Condition:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->tooth_condition }}</p>
                                </div>
                            @endif
                            @if($record->xray_findings)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">X-Ray Findings:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->xray_findings }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Diagnosis & Treatment -->
                @if($record->diagnosis || $record->treatment_plan || $record->treatment_performed || $record->teeth_numbers)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-stethoscope text-red-500 mr-2"></i> Diagnosis & Treatment
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($record->diagnosis)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Diagnosis:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->diagnosis }}</p>
                                </div>
                            @endif
                            @if($record->treatment_plan)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Treatment Plan:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->treatment_plan }}</p>
                                </div>
                            @endif
                            @if($record->treatment_performed)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Treatment Performed:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->treatment_performed }}</p>
                                </div>
                            @endif
                            @if($record->teeth_numbers)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Teeth Numbers:</strong>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $record->teeth_numbers }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Prescriptions & Recommendations -->
                @if($record->prescription || $record->recommendations)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-pills text-orange-500 mr-2"></i> Prescriptions & Recommendations
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($record->prescription)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Prescription:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->prescription }}</p>
                                </div>
                            @endif
                            @if($record->recommendations)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Recommendations:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->recommendations }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Follow-up & Notes -->
                @if($record->next_visit || $record->notes)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-clipboard text-indigo-500 mr-2"></i> Follow-up & Notes
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @if($record->next_visit)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Next Visit:</strong>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $record->next_visit->format('F d, Y') }}</p>
                                </div>
                            @endif
                            @if($record->notes)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <strong class="block text-gray-700 dark:text-gray-300 mb-1">Additional Notes:</strong>
                                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $record->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Attachments -->
                @if($record->attachments && count($record->attachments) > 0)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-paperclip text-gray-500 mr-2"></i> Attachments
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($record->attachments as $attachment)
                                @php
                                    $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($extension) === 'pdf';
                                @endphp
                                
                                <a href="{{ asset('storage/' . $attachment) }}" 
                                   target="_blank"
                                   class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg hover:shadow-lg transition-all text-center">
                                    @if($isPdf)
                                        <i class="fas fa-file-pdf text-red-500 text-4xl mb-2"></i>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">PDF Document</p>
                                    @else
                                        <img src="{{ asset('storage/' . $attachment) }}" 
                                             alt="Attachment" 
                                             class="w-full h-32 object-cover rounded mb-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Image</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>

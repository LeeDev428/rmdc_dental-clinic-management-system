<x-app-layout>
    @section('title', 'My Dental Records')
    
    <style>
        .records-container {
            min-height: 80vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .records-header {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .record-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .record-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .record-date {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        
        .record-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f3f4f6;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }
        
        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .info-value {
            font-size: 0.9375rem;
            color: #374151;
            line-height: 1.5;
        }
        
        .view-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: #3b82f6;
            color: white;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .view-btn:hover {
            background: #2563eb;
            transform: scale(1.02);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #ffffff;
            border-radius: 12px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1.5rem;
        }
        
        .empty-text {
            font-size: 1rem;
            color: #6b7280;
            max-width: 400px;
            margin: 0 auto;
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <div class="records-container py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="records-header">
                <h1 class="text-3xl font-bold text-gray-900">My Dental Records</h1>
                <p class="text-sm text-gray-600 mt-2">Complete history of your dental visits and treatments</p>
            </div>

            @if($records->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-folder-open empty-icon"></i>
                    <p class="empty-text">No dental records available yet. Your records will appear here after your dental visits.</p>
                </div>
            @else
                @foreach($records as $record)
                    <div class="record-card">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="record-date">
                                    {{ $record->visit_date->format('F d, Y') }}
                                </div>
                                @if($record->dentist)
                                    <div class="record-meta">
                                        <i class="fas fa-user-md"></i>
                                        <span>Dr. {{ $record->dentist->name }}</span>
                                    </div>
                                @endif
                                
                                <div class="info-grid">
                                    @if($record->chief_complaint)
                                        <div class="info-item">
                                            <div class="info-label">Chief Complaint</div>
                                            <div class="info-value">{{ \Illuminate\Support\Str::limit($record->chief_complaint, 80) }}</div>
                                        </div>
                                    @endif

                                    @if($record->diagnosis)
                                        <div class="info-item">
                                            <div class="info-label">Diagnosis</div>
                                            <div class="info-value">{{ \Illuminate\Support\Str::limit($record->diagnosis, 80) }}</div>
                                        </div>
                                    @endif

                                    @if($record->treatment_performed)
                                        <div class="info-item">
                                            <div class="info-label">Treatment</div>
                                            <div class="info-value">{{ \Illuminate\Support\Str::limit($record->treatment_performed, 80) }}</div>
                                        </div>
                                    @endif

                                    @if($record->next_visit)
                                        <div class="info-item">
                                            <div class="info-label">Next Visit</div>
                                            <div class="info-value">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ $record->next_visit->format('M d, Y') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <a href="{{ route('patient.dental_record.show', $record->id) }}" class="view-btn">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>

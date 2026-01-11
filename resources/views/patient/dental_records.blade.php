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
        
        .teeth-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: #10b981;
            color: white;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        
        .teeth-btn:hover {
            background: #059669;
            transform: scale(1.02);
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        /* Teeth Chart Modal Styles */
        .teeth-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .teeth-modal-overlay.active {
            display: flex;
        }
        
        .teeth-modal {
            background: white;
            border-radius: 16px;
            max-width: 700px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .teeth-modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .teeth-modal-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }
        
        .teeth-modal-close {
            background: #f3f4f6;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.25rem;
            color: #6b7280;
            transition: all 0.2s;
        }
        
        .teeth-modal-close:hover {
            background: #e5e7eb;
            color: #111827;
        }
        
        .teeth-modal-body {
            padding: 1.5rem;
        }
        
        /* Simple Teeth Chart Display */
        .patient-chart {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .chart-jaw {
            margin-bottom: 0.5rem;
        }
        
        .chart-jaw:last-child {
            margin-bottom: 0;
        }
        
        .chart-row {
            display: flex;
            justify-content: center;
            gap: 3px;
        }
        
        .chart-quadrant {
            display: flex;
            gap: 3px;
        }
        
        .chart-quadrant.right {
            flex-direction: row-reverse;
        }
        
        .chart-divider {
            width: 2px;
            background: #94a3b8;
            margin: 0 6px;
        }
        
        .chart-tooth {
            width: 32px;
            height: 42px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .chart-tooth:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .chart-tooth-num {
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .chart-bite-line {
            text-align: center;
            padding: 0.5rem 0;
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }
        
        /* Legend */
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #475569;
        }
        
        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }
        
        /* Tooth Info Panel */
        .tooth-info-panel {
            display: none;
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .tooth-info-panel.active {
            display: block;
        }
        
        .tooth-info-header {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .tooth-info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.375rem 0;
            font-size: 0.875rem;
        }
        
        .tooth-info-label {
            color: #64748b;
        }
        
        .tooth-info-value {
            font-weight: 500;
            color: #111827;
        }
        
        .tooth-notes {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: white;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        
        .tooth-notes-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        
        .tooth-note-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }
        
        .tooth-note-item:last-child {
            border-bottom: none;
        }
        
        .tooth-note-date {
            font-size: 0.7rem;
            color: #94a3b8;
        }
        
        .no-data-msg {
            text-align: center;
            padding: 2rem;
            color: #64748b;
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
                            
                            <div class="btn-group">
                                <a href="{{ route('patient.dental_record.show', $record->id) }}" class="view-btn">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                                <button type="button" class="teeth-btn" onclick="openTeethChart({{ auth()->id() }}, '{{ $record->visit_date->format('M d, Y') }}')">
                                    <i class="fas fa-tooth"></i>
                                    View Teeth Chart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    
    <!-- Teeth Chart Modal -->
    <div class="teeth-modal-overlay" id="teethModal" onclick="closeTeethModal(event)">
        <div class="teeth-modal" onclick="event.stopPropagation()">
            <div class="teeth-modal-header">
                <h3><i class="fas fa-tooth" style="color: #10b981; margin-right: 0.5rem;"></i>My Teeth Chart</h3>
                <button type="button" class="teeth-modal-close" onclick="closeTeethModal()">&times;</button>
            </div>
            <div class="teeth-modal-body">
                <!-- Legend -->
                <div class="chart-legend">
                    <div class="legend-item"><div class="legend-dot" style="background: #10b981;"></div>Healthy</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #fbbf24;"></div>Watch</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #f59e0b;"></div>Cavity</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #ef4444;"></div>Needs Treatment</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #8b5cf6;"></div>Crown</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #3b82f6;"></div>Filling</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #ec4899;"></div>Root Canal</div>
                    <div class="legend-item"><div class="legend-dot" style="background: #6b7280;"></div>Missing</div>
                </div>
                
                <!-- Teeth Chart -->
                <div class="patient-chart">
                    <!-- Upper Jaw -->
                    <div class="chart-jaw">
                        <div class="chart-row">
                            <div class="chart-quadrant right" id="patient-upper-right"></div>
                            <div class="chart-divider"></div>
                            <div class="chart-quadrant left" id="patient-upper-left"></div>
                        </div>
                    </div>
                    
                    <div class="chart-bite-line">— Bite Line —</div>
                    
                    <!-- Lower Jaw -->
                    <div class="chart-jaw">
                        <div class="chart-row">
                            <div class="chart-quadrant right" id="patient-lower-right"></div>
                            <div class="chart-divider"></div>
                            <div class="chart-quadrant left" id="patient-lower-left"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Tooth Info Panel (shown when clicking a tooth) -->
                <div class="tooth-info-panel" id="toothInfoPanel">
                    <div class="tooth-info-header">Tooth #<span id="toothNumber"></span></div>
                    <div class="tooth-info-row">
                        <span class="tooth-info-label">Condition:</span>
                        <span class="tooth-info-value" id="toothCondition">-</span>
                    </div>
                    <div class="tooth-notes" id="toothNotesSection" style="display: none;">
                        <div class="tooth-notes-title">Notes</div>
                        <div id="toothNotesList"></div>
                    </div>
                </div>
                
                <div class="no-data-msg" id="noDataMsg" style="display: none;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    Click on any tooth to see its details
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Teeth data will be loaded here
        let patientTeethData = [];
        
        const conditionColors = {
            'healthy': '#10b981',
            'watch': '#fbbf24',
            'cavity': '#f59e0b',
            'treatment_needed': '#ef4444',
            'crown': '#8b5cf6',
            'filling': '#3b82f6',
            'root_canal': '#ec4899',
            'missing': '#6b7280'
        };
        
        const conditionLabels = {
            'healthy': 'Healthy',
            'watch': 'Watch',
            'cavity': 'Cavity',
            'treatment_needed': 'Needs Treatment',
            'crown': 'Crown',
            'filling': 'Filling',
            'root_canal': 'Root Canal',
            'missing': 'Missing'
        };
        
        function openTeethChart(patientId, visitDate) {
            document.getElementById('teethModal').classList.add('active');
            document.getElementById('toothInfoPanel').classList.remove('active');
            document.getElementById('noDataMsg').style.display = 'block';
            
            // Fetch teeth data
            fetch(`/patient/teeth-chart/${patientId}`)
                .then(response => response.json())
                .then(data => {
                    patientTeethData = data.teeth || [];
                    renderPatientChart();
                })
                .catch(error => {
                    console.error('Error loading teeth data:', error);
                    // Still render chart with defaults
                    patientTeethData = [];
                    renderPatientChart();
                });
        }
        
        function closeTeethModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('teethModal').classList.remove('active');
        }
        
        function renderPatientChart() {
            const quadrants = {
                'patient-upper-right': [1, 2, 3, 4, 5, 6, 7, 8],
                'patient-upper-left': [9, 10, 11, 12, 13, 14, 15, 16],
                'patient-lower-right': [32, 31, 30, 29, 28, 27, 26, 25],
                'patient-lower-left': [24, 23, 22, 21, 20, 19, 18, 17]
            };
            
            for (const [containerId, teeth] of Object.entries(quadrants)) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';
                
                teeth.forEach(num => {
                    const tooth = createPatientTooth(num);
                    container.appendChild(tooth);
                });
            }
        }
        
        function createPatientTooth(number) {
            const record = patientTeethData.find(t => t.tooth_number === number);
            const condition = record ? record.condition : 'healthy';
            const color = conditionColors[condition] || conditionColors['healthy'];
            
            const tooth = document.createElement('div');
            tooth.className = 'chart-tooth';
            tooth.style.backgroundColor = color;
            tooth.innerHTML = `<span class="chart-tooth-num">${number}</span>`;
            tooth.onclick = () => showToothInfo(number, record);
            
            return tooth;
        }
        
        function showToothInfo(number, record) {
            document.getElementById('noDataMsg').style.display = 'none';
            document.getElementById('toothInfoPanel').classList.add('active');
            document.getElementById('toothNumber').textContent = number;
            
            const condition = record ? record.condition : 'healthy';
            document.getElementById('toothCondition').textContent = conditionLabels[condition] || 'Healthy';
            
            // Handle notes
            const notesSection = document.getElementById('toothNotesSection');
            const notesList = document.getElementById('toothNotesList');
            
            if (record && record.notes && record.notes.length > 0) {
                notesSection.style.display = 'block';
                notesList.innerHTML = record.notes.map(note => `
                    <div class="tooth-note-item">
                        <div>${note.note}</div>
                        <div class="tooth-note-date">${note.created_at || ''}</div>
                    </div>
                `).join('');
            } else {
                notesSection.style.display = 'none';
                notesList.innerHTML = '';
            }
        }
    </script>
</x-app-layout>

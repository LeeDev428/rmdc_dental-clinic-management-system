<div id="teeth-layout-container" class="d-none">
    <!-- Back Button -->
    <div class="back-btn">
        <button class="btn btn-secondary btn-sm" onclick="goBackToList()">
            <i class="fas fa-arrow-left"></i> Back to Patient List
        </button>
    </div>

    <!-- Patient Header -->
    <div class="patient-header">
        <div class="patient-info-left">
            <h3><i class="fas fa-user"></i> <span id="patient-name">Patient Name</span></h3>
            <span id="patient-id">ID: #000</span>
        </div>
        <button class="btn btn-success btn-sm" onclick="initializeDefaultLayout()">
            <i class="fas fa-plus"></i> Initialize 32 Teeth
        </button>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-box"><span id="stat-total" class="stat-num">0</span><small>Total Teeth</small></div>
        <div class="stat-box good"><span id="stat-healthy" class="stat-num">0</span><small>Healthy</small></div>
        <div class="stat-box bad"><span id="stat-treatment" class="stat-num">0</span><small>Needs Treatment</small></div>
    </div>

    <!-- Legend -->
    <div class="legend-bar">
        <span class="legend-item"><i class="dot" style="background:#10b981"></i> Healthy</span>
        <span class="legend-item"><i class="dot" style="background:#fbbf24"></i> Watch</span>
        <span class="legend-item"><i class="dot" style="background:#f59e0b"></i> Cavity</span>
        <span class="legend-item"><i class="dot" style="background:#ef4444"></i> Treatment</span>
        <span class="legend-item"><i class="dot" style="background:#8b5cf6"></i> Crown</span>
        <span class="legend-item"><i class="dot" style="background:#3b82f6"></i> Filling</span>
        <span class="legend-item"><i class="dot" style="background:#ec4899"></i> Root Canal</span>
        <span class="legend-item"><i class="dot" style="background:#6b7280"></i> Missing</span>
    </div>

    <!-- Curved Arch Dental Chart -->
    <div class="dental-chart-card">
        <div class="chart-header">
            <span><i class="fas fa-tooth"></i> Dental Chart</span>
            <small>Click any tooth to edit</small>
        </div>
        
        <div class="arch-chart">
            <div class="mouth-container">
                <!-- Upper Arch (Like looking down at upper jaw) -->
                <div class="upper-arch">
                    <div class="upper-teeth-row">
                        <div class="arch-quadrant right" id="upper-right"></div>
                        <div class="arch-divider-v"></div>
                        <div class="arch-quadrant left" id="upper-left"></div>
                    </div>
                </div>
                
                <!-- Bite Line Divider -->
                <div class="arch-divider"></div>
                
                <!-- Lower Arch (Like looking up at lower jaw) -->
                <div class="lower-arch">
                    <div class="lower-teeth-row">
                        <div class="arch-quadrant right" id="lower-right"></div>
                        <div class="arch-divider-v"></div>
                        <div class="arch-quadrant left" id="lower-left"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


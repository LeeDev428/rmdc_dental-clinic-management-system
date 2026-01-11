<div id="teeth-layout-container" class="d-none">
    <!-- Patient Header -->
    <div class="patient-header">
        <div class="patient-info-left">
            <h3 id="patient-name">Patient Name</h3>
            <span id="patient-id">ID: #000</span>
        </div>
        <button class="btn btn-success btn-sm" onclick="initializeDefaultLayout()">
            <i class="fas fa-plus"></i> Initialize 32 Teeth
        </button>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-box"><span id="stat-total" class="stat-num">0</span><small>Total</small></div>
        <div class="stat-box good"><span id="stat-healthy" class="stat-num">0</span><small>Healthy</small></div>
        <div class="stat-box bad"><span id="stat-treatment" class="stat-num">0</span><small>Treatment</small></div>
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

    <!-- Simple Dental Chart -->
    <div class="dental-chart-card">
        <div class="chart-header">
            <span><i class="fas fa-tooth"></i> Dental Chart</span>
            <small>Click any tooth to edit</small>
        </div>
        
        <div class="simple-chart">
            <!-- Upper Jaw -->
            <div class="jaw-section upper">
                <div class="jaw-title">UPPER</div>
                <div class="teeth-row">
                    <div class="quadrant right" id="upper-right"></div>
                    <div class="divider"></div>
                    <div class="quadrant left" id="upper-left"></div>
                </div>
                <div class="tooth-numbers">
                    <span class="q-label">8 7 6 5 4 3 2 1</span>
                    <span class="q-label">1 2 3 4 5 6 7 8</span>
                </div>
            </div>
            
            <div class="bite-line"><span>— Bite Line —</span></div>
            
            <!-- Lower Jaw -->
            <div class="jaw-section lower">
                <div class="tooth-numbers">
                    <span class="q-label">8 7 6 5 4 3 2 1</span>
                    <span class="q-label">1 2 3 4 5 6 7 8</span>
                </div>
                <div class="teeth-row">
                    <div class="quadrant right" id="lower-right"></div>
                    <div class="divider"></div>
                    <div class="quadrant left" id="lower-left"></div>
                </div>
                <div class="jaw-title">LOWER</div>
            </div>
        </div>
    </div>
</div>


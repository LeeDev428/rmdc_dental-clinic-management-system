<div id="teeth-layout-container" class="d-none">
    <div class="patient-info">
        <div class="patient-name" id="patient-name">Patient Name</div>
        <div class="patient-id" id="patient-id">Patient ID: #000</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="stat-total">32</div>
            <div class="stat-label">Total Teeth</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-healthy" style="color: #10b981;">0</div>
            <div class="stat-label">Healthy</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-treatment" style="color: #ef4444;">0</div>
            <div class="stat-label">Need Treatment</div>
        </div>
    </div>

    <div class="legend-section">
        <div class="legend-title">Condition Legend</div>
        <div class="legend-grid">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #10b981;"></div>
                <span>Healthy</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fbbf24;"></div>
                <span>Watch</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #f59e0b;"></div>
                <span>Cavity</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ef4444;"></div>
                <span>Treatment</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #8b5cf6;"></div>
                <span>Crown</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #3b82f6;"></div>
                <span>Implant</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ec4899;"></div>
                <span>Root Canal</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #6b7280;"></div>
                <span>Missing</span>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="chart-title">Interactive Dental Chart</div>
        <div class="dental-chart">
            <svg id="teeth-chart" viewBox="0 0 600 800" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 600px; height: auto;">
                <!-- Upper Arch Background -->
                <ellipse cx="300" cy="200" rx="200" ry="120" fill="#e91e63" opacity="0.3"/>
                <ellipse cx="300" cy="200" rx="160" ry="90" fill="#f48fb1" opacity="0.5"/>
                
                <!-- Lower Arch Background -->
                <ellipse cx="300" cy="600" rx="200" ry="120" fill="#e91e63" opacity="0.3"/>
                <ellipse cx="300" cy="600" rx="160" ry="90" fill="#f48fb1" opacity="0.5"/>
                
                <!-- Arch Labels -->
                <text x="500" y="200" class="quadrant-label" font-size="20" font-weight="bold">Upper Arch</text>
                <text x="500" y="600" class="quadrant-label" font-size="20" font-weight="bold">Lower Arch</text>
            </svg>
        </div>
        <div class="action-buttons">
            <button type="button" class="btn btn-success" onclick="initializeDefaultLayout()">Initialize Default Layout</button>
        </div>
    </div>
</div>

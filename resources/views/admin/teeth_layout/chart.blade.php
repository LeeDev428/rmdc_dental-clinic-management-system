<!-- Patient Info -->
<div class="patient-info">
    <div class="patient-name" id="patient-name">Patient Name</div>
    <div class="patient-id" id="patient-id">Patient ID: #000</div>
</div>

<!-- Statistics -->
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

<!-- Legend -->
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

<!-- Dental Chart -->
<div class="content-card chart-container">
    <div class="chart-title">Interactive Dental Chart</div>
    <div class="dental-chart">
        <svg id="teeth-chart" viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 600px;">
            <!-- Quadrant lines -->
            <line x1="400" y1="50" x2="400" y2="550" stroke="#d1d5db" stroke-width="1" stroke-dasharray="5,5"/>
            <line x1="100" y1="300" x2="700" y2="300" stroke="#d1d5db" stroke-width="1" stroke-dasharray="5,5"/>
            
            <!-- Quadrant Labels -->
            <text x="250" y="80" class="quadrant-label">Upper Right (1-8)</text>
            <text x="480" y="80" class="quadrant-label">Upper Left (9-16)</text>
            <text x="480" y="540" class="quadrant-label">Lower Left (17-24)</text>
            <text x="230" y="540" class="quadrant-label">Lower Right (25-32)</text>
        </svg>
    </div>
    <div class="action-buttons">
        <button type="button" class="btn btn-success" onclick="initializeDefaultLayout()">
            Initialize Default Layout
        </button>
    </div>
</div>

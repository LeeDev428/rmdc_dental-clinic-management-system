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
            <svg id="teeth-chart" viewBox="0 0 700 900" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 700px; height: auto;">
                <!-- Upper Arch Background -->
                <ellipse cx="350" cy="230" rx="280" ry="140" fill="#f8bbd0" opacity="0.6"/>
                <ellipse cx="350" cy="230" rx="240" ry="110" fill="#f48fb1" opacity="0.4"/>
                
                <!-- Lower Arch Background -->
                <ellipse cx="350" cy="670" rx="280" ry="140" fill="#f8bbd0" opacity="0.6"/>
                <ellipse cx="350" cy="670" rx="240" ry="110" fill="#f48fb1" opacity="0.4"/>
                
                <!-- Labels -->
                <text x="600" y="230" fill="#9ca3af" font-size="16" font-weight="600">Upper Arch</text>
                <text x="600" y="670" fill="#9ca3af" font-size="16" font-weight="600">Lower Arch</text>
            </svg>
        </div>
        <div class="action-buttons">
            <button type="button" class="btn btn-success" onclick="initializeDefaultLayout()">Initialize Default Layout</button>
        </div>
    </div>
</div>

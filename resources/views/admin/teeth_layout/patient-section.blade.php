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
            <svg id="teeth-chart" viewBox="0 0 500 700" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 500px; height: auto;">
                <!-- Upper Arch - U-shaped gum -->
                <path d="M 80,180 Q 80,80 250,80 Q 420,80 420,180 L 420,220 Q 420,240 400,240 L 100,240 Q 80,240 80,220 Z" 
                      fill="#f48fb1" stroke="none"/>
                <path d="M 100,200 Q 100,100 250,100 Q 400,100 400,200" 
                      fill="#f8a5c2" stroke="none"/>
                
                <!-- Lower Arch - U-shaped gum -->
                <path d="M 80,520 Q 80,620 250,620 Q 420,620 420,520 L 420,480 Q 420,460 400,460 L 100,460 Q 80,460 80,480 Z" 
                      fill="#f48fb1" stroke="none"/>
                <path d="M 100,500 Q 100,600 250,600 Q 400,600 400,500" 
                      fill="#f8a5c2" stroke="none"/>
                
                <!-- Labels -->
                <text x="430" y="150" fill="#9ca3af" font-size="14" font-weight="600">Upper Arch</text>
                <text x="430" y="550" fill="#9ca3af" font-size="14" font-weight="600">Lower Arch</text>
            </svg>
        </div>
        <div class="action-buttons">
            <button type="button" class="btn btn-success" onclick="initializeDefaultLayout()">Initialize Default Layout</button>
        </div>
    </div>
</div>

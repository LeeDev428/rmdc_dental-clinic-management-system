<style>
/* Base */
.page-header { background:#fff; padding:20px 24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:20px; display:flex; align-items:center; gap:12px; }
.page-title { font-size:22px; font-weight:600; color:#1a1a1a; margin:0; }
.page-title i { color:#10b981; }
.content-card { background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.08); padding:20px; margin-bottom:20px; }
.d-none { display:none !important; }

/* Table Styles */
.table-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px; }
.table-header h4 { margin:0; font-size:16px; color:#1e293b; display:flex; align-items:center; gap:8px; }
.table-header h4 i { color:#3b82f6; }
.table-responsive { overflow-x:auto; }
.patient-table { width:100%; border-collapse:collapse; }
.patient-table th { background:#f8fafc; padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; }
.patient-table td { padding:12px 16px; border-bottom:1px solid #f1f5f9; font-size:14px; color:#334155; }
.patient-table tbody tr:hover { background:#f8fafc; }
.patient-table tbody tr:last-child td { border-bottom:none; }

/* Patient Header */
.patient-header { display:flex; justify-content:space-between; align-items:center; background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%); padding:16px 20px; border-radius:8px; margin-bottom:16px; border:1px solid #bbf7d0; }
.patient-info-left h3 { margin:0 0 4px; font-size:18px; color:#166534; }
.patient-info-left span { font-size:13px; color:#15803d; }

/* Stats Row */
.stats-row { display:flex; gap:12px; margin-bottom:16px; }
.stat-box { flex:1; background:#f8fafc; padding:14px; border-radius:8px; text-align:center; }
.stat-box .stat-num { display:block; font-size:26px; font-weight:700; color:#1e293b; }
.stat-box small { color:#64748b; font-size:12px; }
.stat-box.good .stat-num { color:#10b981; }
.stat-box.bad .stat-num { color:#ef4444; }

/* Legend Bar */
.legend-bar { display:flex; flex-wrap:wrap; gap:12px; padding:12px 16px; background:#f8fafc; border-radius:8px; margin-bottom:16px; }
.legend-bar .legend-item { display:flex; align-items:center; gap:6px; font-size:12px; color:#475569; }
.legend-bar .dot { width:12px; height:12px; border-radius:3px; display:inline-block; }

/* Dental Chart Card */
.dental-chart-card { background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; }
.chart-header { display:flex; justify-content:space-between; align-items:center; padding:14px 20px; background:linear-gradient(135deg,#f0fdf4 0%,#ecfdf5 100%); border-bottom:1px solid #d1fae5; }
.chart-header span { font-weight:600; color:#166534; display:flex; align-items:center; gap:8px; }
.chart-header span i { color:#10b981; }
.chart-header small { color:#15803d; font-size:12px; }

/* Curved Arch Chart */
.arch-chart { padding:30px 20px; background:linear-gradient(180deg,#fafafa 0%,#f5f5f5 100%); position:relative; }
.mouth-container { max-width:420px; margin:0 auto; position:relative; }

/* Upper Arch - Real U-shape (inverted U for upper) */
.upper-arch { 
    position: relative;
    padding: 20px 20px 15px;
    background: linear-gradient(180deg, #fecdd3 0%, #fda4af 100%);
    border-radius: 0 0 50% 50% / 0 0 100% 100%;
    border: 4px solid #f87171;
    border-top: none;
    min-height: 120px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}
.upper-arch::before {
    content: 'UPPER';
    position: absolute;
    top: -28px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 11px;
    font-weight: 700;
    color: #dc2626;
    letter-spacing: 3px;
    background: #f5f5f5;
    padding: 4px 12px;
    border-radius: 4px;
}
.upper-teeth-row {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 2px;
    position: relative;
    padding-top: 10px;
}

/* Lower Arch - U-shape */
.lower-arch { 
    position: relative;
    padding: 15px 20px 20px;
    background: linear-gradient(0deg, #fecdd3 0%, #fda4af 100%);
    border-radius: 50% 50% 0 0 / 100% 100% 0 0;
    border: 4px solid #f87171;
    border-bottom: none;
    min-height: 120px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}
.lower-arch::after {
    content: 'LOWER';
    position: absolute;
    bottom: -28px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 11px;
    font-weight: 700;
    color: #dc2626;
    letter-spacing: 3px;
    background: #f5f5f5;
    padding: 4px 12px;
    border-radius: 4px;
}
.lower-teeth-row {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 2px;
    position: relative;
    padding-bottom: 10px;
}

/* Divider between arches */
.arch-divider {
    height: 6px;
    background: #f87171;
    margin: 0;
    position: relative;
}
.arch-divider::before {
    content: '— Bite Line —';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 4px 16px;
    font-size: 11px;
    color: #94a3b8;
    letter-spacing: 1px;
    white-space: nowrap;
    border-radius: 4px;
}

/* Individual Teeth in Arch */
.arch-quadrant {
    display: flex;
    gap: 2px;
    align-items: flex-end;
}
.upper-arch .arch-quadrant {
    align-items: flex-end;
}
.lower-arch .arch-quadrant {
    align-items: flex-start;
}
.arch-quadrant.right { 
    flex-direction: row-reverse; 
}
.arch-divider-v {
    width: 3px;
    background: #dc2626;
    margin: 0 6px;
    align-self: stretch;
    border-radius: 2px;
}

/* Tooth with curved positioning - Upper arch teeth curve DOWN into mouth (molars higher) */
.upper-arch .arch-quadrant .tooth:nth-child(1) { margin-top: 22px; }
.upper-arch .arch-quadrant .tooth:nth-child(2) { margin-top: 16px; }
.upper-arch .arch-quadrant .tooth:nth-child(3) { margin-top: 11px; }
.upper-arch .arch-quadrant .tooth:nth-child(4) { margin-top: 7px; }
.upper-arch .arch-quadrant .tooth:nth-child(5) { margin-top: 4px; }
.upper-arch .arch-quadrant .tooth:nth-child(6) { margin-top: 2px; }
.upper-arch .arch-quadrant .tooth:nth-child(7) { margin-top: 1px; }
.upper-arch .arch-quadrant .tooth:nth-child(8) { margin-top: 0px; }

/* Lower arch teeth curve UP into mouth (molars lower) */
.lower-arch .arch-quadrant .tooth:nth-child(1) { margin-bottom: 22px; }
.lower-arch .arch-quadrant .tooth:nth-child(2) { margin-bottom: 16px; }
.lower-arch .arch-quadrant .tooth:nth-child(3) { margin-bottom: 11px; }
.lower-arch .arch-quadrant .tooth:nth-child(4) { margin-bottom: 7px; }
.lower-arch .arch-quadrant .tooth:nth-child(5) { margin-bottom: 4px; }
.lower-arch .arch-quadrant .tooth:nth-child(6) { margin-bottom: 2px; }
.lower-arch .arch-quadrant .tooth:nth-child(7) { margin-bottom: 1px; }
.lower-arch .arch-quadrant .tooth:nth-child(8) { margin-bottom: 0px; }

/* Tooth with realistic shape */
.tooth { 
    width:34px; 
    height:48px; 
    border-radius:8px 8px 14px 14px;
    cursor:pointer; 
    display:flex; 
    align-items:center; 
    justify-content:center;
    font-size:11px; 
    font-weight:600; 
    color:#fff;
    transition:all .2s ease;
    box-shadow:0 2px 4px rgba(0,0,0,.2), inset 0 1px 0 rgba(255,255,255,.3);
    position:relative;
    border:2px solid rgba(255,255,255,.4);
}
.tooth:hover { 
    transform:translateY(-4px) scale(1.05); 
    box-shadow:0 6px 12px rgba(0,0,0,.25); 
    z-index:10;
}
.tooth.missing { opacity:0.25; background:#9ca3af !important; }
.tooth .tooth-num { text-shadow:0 1px 2px rgba(0,0,0,.4); }
.tooth.has-notes::after {
    content:''; 
    position:absolute; 
    top:-4px; 
    right:-4px;
    width:12px; 
    height:12px; 
    background:#fbbf24; 
    border-radius:50%;
    border:2px solid #fff;
    box-shadow:0 1px 3px rgba(0,0,0,.2);
}

/* Lower teeth have inverted shape */
.lower-arch .tooth {
    border-radius:14px 14px 8px 8px;
}

/* Buttons */
.btn { padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500; border:none; cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:6px; }
.btn:hover { transform:translateY(-1px); }
.btn-sm { padding:6px 12px; font-size:12px; }
.btn-primary { background:#3b82f6; color:#fff; }
.btn-primary:hover { background:#2563eb; }
.btn-success { background:#10b981; color:#fff; }
.btn-success:hover { background:#059669; }
.btn-secondary { background:#64748b; color:#fff; }
.btn-secondary:hover { background:#475569; }
.btn-danger { background:#ef4444; color:#fff; }
.btn-danger:hover { background:#dc2626; }

/* Modal */
.modal { position:fixed; top:0; left:0; width:100%; height:100%; display:none; justify-content:center; align-items:center; z-index:9999; padding:20px; }
.modal.show { display:flex !important; }
.modal-backdrop { position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); }
.modal-dialog { background:#fff; border-radius:12px; width:90%; max-width:500px; max-height:85vh; overflow-y:auto; position:relative; z-index:2; animation:modalIn .2s ease; }
@keyframes modalIn { from { opacity:0; transform:scale(.95); } to { opacity:1; transform:scale(1); } }
.modal-header { padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
.modal-title { font-size:16px; font-weight:600; color:#1e293b; margin:0; }
.modal-body { padding:20px; }
.modal-footer { padding:14px 20px; border-top:1px solid #e2e8f0; display:flex; gap:10px; justify-content:flex-end; }
.close { background:none; border:none; font-size:24px; color:#94a3b8; cursor:pointer; padding:0; line-height:1; }
.close:hover { color:#ef4444; }

/* Form Elements in Modal */
.form-label { display:block; font-weight:500; color:#4a4a4a; margin-bottom:6px; font-size:13px; }
.form-control { width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
.form-control:focus { outline:none; border-color:#3b82f6; }
.form-group { margin-bottom:16px; }
.form-select { width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:6px; font-size:14px; background:#fff; }
.form-select:focus { outline:none; border-color:#3b82f6; }
textarea.form-control { min-height:80px; resize:vertical; }
.detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
.detail-item { padding:10px; background:#f8fafc; border-radius:6px; }
.detail-label { font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:2px; }
.detail-value { font-size:14px; color:#1e293b; font-weight:500; }

/* Notes in Modal */
.notes-section { margin-top:16px; padding-top:16px; border-top:1px solid #e2e8f0; }
.section-title { font-size:13px; font-weight:600; color:#1e293b; margin-bottom:10px; }
.note-item { padding:10px; background:#f8fafc; border-radius:6px; margin-bottom:8px; font-size:13px; }
.note-header { display:flex; justify-content:space-between; margin-bottom:4px; }
.note-type { font-size:10px; padding:2px 6px; background:#e2e8f0; border-radius:3px; font-weight:600; text-transform:uppercase; }
.note-date { font-size:11px; color:#64748b; }
.note-content { color:#475569; line-height:1.4; }
.text-muted { color:#94a3b8; }
.text-center { text-align:center; }

/* Back Button */
.back-btn { margin-bottom:16px; }

/* Responsive */
@media (max-width:768px) {
    .stats-row { flex-wrap:wrap; }
    .stat-box { min-width:calc(33% - 8px); }
    .legend-bar { justify-content:center; }
    .tooth { width:28px; height:40px; font-size:9px; }
    .arch-divider-v { margin:0 4px; }
    .detail-grid { grid-template-columns:1fr; }
    .table-header { flex-direction:column; align-items:stretch; }
    .table-header input { max-width:100% !important; }
}
</style>

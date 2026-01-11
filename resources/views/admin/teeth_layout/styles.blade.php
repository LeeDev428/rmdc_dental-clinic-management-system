<style>
/* Base */
.page-header { background:#fff; padding:20px 24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:20px; }
.page-title { font-size:22px; font-weight:600; color:#1a1a1a; margin:0; }
.content-card { background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.08); padding:20px; margin-bottom:20px; }
.d-none { display:none !important; }
.d-flex { display:flex; }
.justify-content-between { justify-content:space-between; }
.align-items-center { align-items:center; }

/* Search */
.search-section { margin-bottom:20px; }
.form-label { display:block; font-weight:500; color:#4a4a4a; margin-bottom:6px; font-size:13px; }
.form-control { width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
.form-control:focus { outline:none; border-color:#3b82f6; }
.list-group { margin-top:8px; max-height:200px; overflow-y:auto; border:1px solid #ddd; border-radius:6px; display:none; background:#fff; }
.list-group-item { padding:10px 14px; border-bottom:1px solid #f0f0f0; cursor:pointer; }
.list-group-item:hover { background:#f8f9fa; }
.list-group-item:last-child { border-bottom:none; }

/* Patient Header */
.patient-header { display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:8px; margin-bottom:16px; }
.patient-info-left h3 { margin:0 0 4px; font-size:18px; color:#1e293b; }
.patient-info-left span { font-size:13px; color:#64748b; }

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
.dental-chart-card { background:#fff; border-radius:8px; border:1px solid #e2e8f0; overflow:hidden; }
.chart-header { display:flex; justify-content:space-between; align-items:center; padding:14px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
.chart-header span { font-weight:600; color:#1e293b; }
.chart-header small { color:#64748b; font-size:12px; }

/* Simple Chart Layout */
.simple-chart { padding:24px; }
.jaw-section { margin-bottom:8px; }
.jaw-section.lower { margin-bottom:0; margin-top:8px; }
.jaw-title { text-align:center; font-size:11px; font-weight:600; color:#94a3b8; letter-spacing:1px; margin:8px 0; }
.teeth-row { display:flex; justify-content:center; align-items:center; gap:4px; }
.quadrant { display:flex; gap:6px; }
.quadrant.right { flex-direction:row-reverse; }
.divider { width:2px; height:50px; background:#cbd5e1; margin:0 12px; border-radius:1px; }
.tooth-numbers { display:flex; justify-content:center; gap:4px; margin:6px 0; }
.tooth-numbers .q-label { width:calc(50% - 20px); text-align:center; font-size:10px; color:#94a3b8; letter-spacing:2px; }
.bite-line { text-align:center; padding:12px 0; }
.bite-line span { font-size:11px; color:#cbd5e1; letter-spacing:2px; }

/* Tooth Element */
.tooth { 
    width:36px; height:48px; 
    border-radius:6px 6px 12px 12px; 
    cursor:pointer; 
    display:flex; align-items:center; justify-content:center;
    font-size:11px; font-weight:600; color:#fff;
    transition:all .15s ease;
    box-shadow:0 2px 4px rgba(0,0,0,.15);
    position:relative;
}
.tooth:hover { transform:translateY(-3px); box-shadow:0 4px 8px rgba(0,0,0,.2); }
.tooth.missing { opacity:0.3; background:#9ca3af !important; }
.tooth .tooth-num { text-shadow:0 1px 2px rgba(0,0,0,.3); }
.tooth.has-notes::after {
    content:''; position:absolute; top:-3px; right:-3px;
    width:10px; height:10px; background:#fbbf24; border-radius:50%;
    border:2px solid #fff;
}

/* Buttons */
.btn { padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500; border:none; cursor:pointer; transition:all .15s; }
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
.close { background:none; border:none; font-size:20px; color:#94a3b8; cursor:pointer; padding:0; }
.close:hover { color:#64748b; }

/* Form Elements in Modal */
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

/* Responsive */
@media (max-width:768px) {
    .stats-row { flex-wrap:wrap; }
    .stat-box { min-width:calc(33% - 8px); }
    .legend-bar { justify-content:center; }
    .tooth { width:30px; height:40px; font-size:10px; }
    .divider { margin:0 8px; }
    .detail-grid { grid-template-columns:1fr; }
}
</style>

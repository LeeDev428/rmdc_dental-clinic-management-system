<style>
.page-header { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 600; color: #1a1a1a; margin: 0; }
.content-card { background-color: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px; }
.search-section { margin-bottom: 24px; }
.form-label { display: block; font-weight: 500; color: #4a4a4a; margin-bottom: 8px; font-size: 14px; }
.form-control { width: 100%; padding: 8px 12px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 14px; }
.form-control:focus { outline: none; border-color: #0084ff; }
.list-group { margin-top: 12px; max-height: 250px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 6px; display: none; background: #fff; }
.list-group-item { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background-color 0.2s; }
.list-group-item:last-child { border-bottom: none; }
.list-group-item:hover { background-color: #f8f9fa; }
.patient-info { background: #f8f9fa; padding: 16px; border-radius: 6px; margin-bottom: 24px; }
.patient-name { font-size: 18px; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; }
.patient-id { font-size: 14px; color: #6b7280; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: #f8f9fa; padding: 16px; border-radius: 6px; text-align: center; }
.stat-value { font-size: 28px; font-weight: 700; color: #1a1a1a; }
.stat-label { font-size: 12px; color: #6b7280; margin-top: 4px; font-weight: 500; }
.chart-title { font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #e0e0e0; }
.dental-chart { width: 100%; max-width: 800px; margin: 0 auto; background: #fafafa; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; }
.tooth-group { cursor: pointer; }
.tooth-group:hover .tooth-shape { filter: brightness(1.15) drop-shadow(3px 3px 5px rgba(0,0,0,0.5)); stroke-width: 2.5; }
.tooth-shape { transition: filter 0.15s ease, stroke-width 0.15s ease; }
.tooth-label { font-weight: 700; pointer-events: none; user-select: none; }
.quadrant-label { font-size: 14px; font-weight: 600; fill: #9ca3af; }
.legend-section { background: #f8f9fa; padding: 16px; border-radius: 6px; margin-bottom: 24px; }
.legend-title { font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 12px; }
.legend-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 8px; }
.legend-item { display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 4px; font-size: 13px; }
.legend-color { width: 20px; height: 20px; border-radius: 3px; border: 1px solid #e0e0e0; }
.action-buttons { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-top: 20px; }
.btn { padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.btn:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
.btn:active { transform: translateY(0); }
.btn-primary { background-color: #0084ff; color: #fff; }
.btn-primary:hover { background-color: #0073e6; }
.btn-success { background-color: #10b981; color: #fff; }
.btn-success:hover { background-color: #059669; }
.btn-secondary { background-color: #6b7280; color: #fff; }
.btn-secondary:hover { background-color: #4b5563; }
.btn-danger { background-color: #ef4444; color: #fff; }
.btn-danger:hover { background-color: #dc2626; }
.d-none { display: none !important; }
.modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; z-index: 9999; padding: 20px; }
.modal.show { display: flex !important; }
.modal-backdrop { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1; }
.modal-dialog { background: #fff; border-radius: 12px; width: 90%; max-width: 650px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); position: relative; z-index: 2; animation: modalSlideIn 0.3s ease-out; }
@keyframes modalSlideIn { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
.modal-header { padding: 20px 24px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
.modal-title { font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0; }
.modal-body { padding: 24px; }
.detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
.detail-item { padding: 12px; background: #f8f9fa; border-radius: 6px; }
.detail-label { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
.detail-value { font-size: 16px; color: #1a1a1a; font-weight: 600; }
.form-group { margin-bottom: 18px; }
.form-select { width: 100%; padding: 10px 14px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 14px; background-color: #fff; cursor: pointer; transition: border-color 0.2s; }
.form-select:hover { border-color: #c0c0c0; }
.form-select:focus { outline: none; border-color: #0084ff; box-shadow: 0 0 0 3px rgba(0,132,255,0.1); }
textarea.form-control { min-height: 90px; resize: vertical; }
textarea.form-control:focus { outline: none; border-color: #0084ff; box-shadow: 0 0 0 3px rgba(0,132,255,0.1); }
.notes-section { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
.section-title { font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 12px; }
.note-item { padding: 12px; background: #f8f9fa; border-radius: 6px; margin-bottom: 8px; }
.note-header { display: flex; justify-content: space-between; margin-bottom: 6px; }
.note-type { font-size: 11px; padding: 2px 8px; border-radius: 3px; font-weight: 600; text-transform: uppercase; background: #e0e0e0; color: #4a4a4a; }
.note-date { font-size: 12px; color: #6b7280; }
.note-content { font-size: 13px; color: #374151; line-height: 1.5; }
.modal-footer { padding: 16px 24px; border-top: 1px solid #e0e0e0; display: flex; gap: 12px; justify-content: flex-end; }
.close { background: none; border: none; font-size: 24px; color: #9ca3af; cursor: pointer; padding: 0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 4px; }
.close:hover { background: #f0f0f0; color: #4a4a4a; }
</style>

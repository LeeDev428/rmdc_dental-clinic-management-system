<div>
    <a href="{{ route('invoice.download', $appointment->id) }}" 
       onclick="event.preventDefault(); downloadInvoicePDF({{ $appointment->id }});"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-200 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>{{ $slot->isEmpty() ? 'Download Invoice' : $slot }}</span>
    </a>
</div>

@once
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
async function downloadInvoicePDF(appointmentId) {
    try {
        // Show loading state
        const originalButton = event.target.closest('a');
        const originalHTML = originalButton.innerHTML;
        originalButton.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...';
        originalButton.disabled = true;

        // Fetch invoice data
        const response = await fetch(`/invoice/${appointmentId}/data`);
        const data = await response.json();

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        
        const pageWidth = 210;
        const pageHeight = 297;
        const margin = 20;
        const contentWidth = pageWidth - (2 * margin);
        let yPos = margin;

        // Colors
        const primaryColor = [37, 99, 235]; // Blue
        const secondaryColor = [59, 130, 246];
        const textColor = [31, 41, 55];
        const grayColor = [107, 114, 128];

        // Header Background
        doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.rect(0, 0, pageWidth, 40, 'F');

        // Clinic Logo/Name
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(24);
        doc.setFont(undefined, 'bold');
        doc.text('RMDC', margin, yPos);
        
        doc.setFontSize(12);
        doc.setFont(undefined, 'normal');
        doc.text('Robles Moncayo Dental Clinic', margin, yPos + 8);

        // Invoice Number (Top Right)
        doc.setFontSize(10);
        doc.text('INVOICE', pageWidth - margin, yPos, { align: 'right' });
        doc.setFontSize(16);
        doc.setFont(undefined, 'bold');
        doc.text(`#${String(data.id).padStart(6, '0')}`, pageWidth - margin, yPos + 8, { align: 'right' });

        yPos = 50;

        // Clinic & Patient Info Section
        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        doc.setFontSize(10);
        doc.setFont(undefined, 'bold');
        doc.text('FROM:', margin, yPos);
        doc.text('TO:', margin + contentWidth/2, yPos);

        yPos += 6;
        doc.setFont(undefined, 'normal');
        doc.setFontSize(9);

        // Clinic Info
        const clinicInfo = [
            'RMDC - Robles Moncayo Dental Clinic',
            'Unit F Medina Bldg, Niog Elementary School',
            'Bacoor, Cavite, Philippines',
            'Email: robles_moncayo@yahoo.com',
            'Phone: (+63) 912-3456-789'
        ];
        clinicInfo.forEach((line, i) => {
            doc.text(line, margin, yPos + (i * 5));
        });

        // Patient Info
        const patientInfo = [
            `Patient: ${data.patient_name}`,
            `Email: ${data.patient_email}`,
            `Date: ${data.appointment_date}`,
            `Time: ${data.appointment_time}`,
            `Status: ${data.status.toUpperCase()}`
        ];
        patientInfo.forEach((line, i) => {
            doc.text(line, margin + contentWidth/2, yPos + (i * 5));
        });

        yPos += 35;

        // Services Table Header
        doc.setFillColor(240, 240, 240);
        doc.rect(margin, yPos, contentWidth, 10, 'F');
        
        doc.setFont(undefined, 'bold');
        doc.setFontSize(9);
        doc.text('DESCRIPTION', margin + 2, yPos + 6);
        doc.text('DURATION', margin + contentWidth - 50, yPos + 6);
        doc.text('AMOUNT', margin + contentWidth - 2, yPos + 6, { align: 'right' });

        yPos += 12;

        // Service Item
        doc.setFont(undefined, 'normal');
        doc.text(data.procedure, margin + 2, yPos);
        doc.text(`${data.duration} mins`, margin + contentWidth - 50, yPos);
        doc.text(`₱${data.price.toFixed(2)}`, margin + contentWidth - 2, yPos, { align: 'right' });

        yPos += 10;
        doc.setDrawColor(200, 200, 200);
        doc.line(margin, yPos, pageWidth - margin, yPos);

        // Payment Summary
        yPos += 10;
        doc.setFontSize(10);
        
        const summaryItems = [
            { label: 'Subtotal:', amount: data.price },
            { label: 'Down Payment (20%):', amount: data.down_payment, isNegative: true },
        ];

        summaryItems.forEach(item => {
            doc.setFont(undefined, 'normal');
            doc.text(item.label, pageWidth - margin - 60, yPos);
            const amountText = `${item.isNegative ? '-' : ''}₱${item.amount.toFixed(2)}`;
            doc.text(amountText, pageWidth - margin - 2, yPos, { align: 'right' });
            yPos += 6;
        });

        // Balance Due (Highlighted)
        yPos += 4;
        doc.setFillColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
        doc.rect(pageWidth - margin - 62, yPos - 4, 62, 10, 'F');
        
        doc.setTextColor(255, 255, 255);
        doc.setFont(undefined, 'bold');
        doc.setFontSize(12);
        doc.text('BALANCE DUE:', pageWidth - margin - 60, yPos + 2);
        const balanceDue = data.price - data.down_payment;
        doc.text(`₱${balanceDue.toFixed(2)}`, pageWidth - margin - 2, yPos + 2, { align: 'right' });

        // Payment Info Box
        yPos += 20;
        doc.setDrawColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
        doc.setLineWidth(0.5);
        doc.rect(margin, yPos, contentWidth, 25);

        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        doc.setFont(undefined, 'bold');
        doc.setFontSize(10);
        doc.text('PAYMENT INFORMATION', margin + 2, yPos + 6);

        doc.setFont(undefined, 'normal');
        doc.setFontSize(9);
        yPos += 12;
        
        const paymentInfo = [
            `Method: ${data.payment_method || 'N/A'}`,
            `Reference: ${data.payment_reference || 'N/A'}`,
            `Status: ${(data.payment_status || 'pending').toUpperCase()}`
        ];

        paymentInfo.forEach((info, i) => {
            doc.text(info, margin + 2, yPos + (i * 5));
        });

        // Footer
        yPos = pageHeight - 30;
        doc.setLineWidth(0.3);
        doc.setDrawColor(grayColor[0], grayColor[1], grayColor[2]);
        doc.line(margin, yPos, pageWidth - margin, yPos);

        yPos += 6;
        doc.setTextColor(grayColor[0], grayColor[1], grayColor[2]);
        doc.setFontSize(8);
        doc.text('For inquiries, contact us at robles_moncayo@yahoo.com', pageWidth/2, yPos, { align: 'center' });
        doc.text(`RMDC - Robles Moncayo Dental Clinic © ${new Date().getFullYear()}`, pageWidth/2, yPos + 5, { align: 'center' });

        // Save PDF
        const fileName = `Invoice_${String(data.id).padStart(6, '0')}_${data.patient_name.replace(/\s+/g, '_')}.pdf`;
        doc.save(fileName);

        // Restore button
        setTimeout(() => {
            originalButton.innerHTML = originalHTML;
            originalButton.disabled = false;
        }, 1000);

    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Failed to generate PDF. Please try again.');
        
        // Restore button on error
        const btn = event.target.closest('a');
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    }
}
</script>
@endpush
@endonce

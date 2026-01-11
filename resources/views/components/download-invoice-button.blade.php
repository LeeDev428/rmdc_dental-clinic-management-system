<!-- Load jsPDF libraries once globally -->
@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endonce

<div>
    <a href="{{ route('invoice.download', $appointment->id) }}" 
       onclick="event.preventDefault(); window.downloadInvoicePDF({{ $appointment->id }}, event);"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-200 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>{{ $slot->isEmpty() ? 'Download Invoice' : $slot }}</span>
    </a>
</div>

@once
<script>
// Make function globally available
window.downloadInvoicePDF = async function(appointmentId, evt) {
    try {
        // Show loading state
        const originalButton = evt.target.closest('a');
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

        // Header Background - Blue bar
        doc.setFillColor(37, 99, 235);
        doc.rect(0, 0, pageWidth, 35, 'F');

        // Clinic Logo/Name - White text on blue
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(22);
        doc.setFont(undefined, 'bold');
        doc.text('RMDC', margin, yPos + 6);
        
        doc.setFontSize(10);
        doc.setFont(undefined, 'normal');
        doc.text('Robles Moncayo Dental Clinic', margin, yPos + 14);

        // Invoice Number (Top Right) - White on blue
        doc.setFontSize(9);
        doc.text('INVOICE', pageWidth - margin, yPos + 4, { align: 'right' });
        doc.setFontSize(14);
        doc.setFont(undefined, 'bold');
        doc.text(`#${String(data.id).padStart(6, '0')}`, pageWidth - margin, yPos + 12, { align: 'right' });

        yPos = 48;

        // Two-column layout with backgrounds
        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        
        // FROM section background (light gray)
        doc.setFillColor(245, 245, 245);
        doc.rect(margin, yPos, contentWidth/2 - 5, 35, 'F');
        
        // TO section background (light green)
        doc.setFillColor(240, 253, 244);
        doc.rect(margin + contentWidth/2 + 5, yPos, contentWidth/2 - 5, 35, 'F');

        // FROM header
        doc.setFontSize(9);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(100, 100, 100);
        doc.text('FROM:', margin + 3, yPos + 6);

        // Clinic Info
        doc.setFontSize(8);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        const clinicInfo = [
            'RMDC - Robles Moncayo Dental Clinic',
            'Unit F Medina Bldg, Niog Elementary School',
            'Bacoor, Cavite, Philippines',
            'Email: robles_moncayo@yahoo.com',
            'Phone: (+63) 912-3456-789'
        ];
        clinicInfo.forEach((line, i) => {
            doc.text(line, margin + 3, yPos + 12 + (i * 4.5));
        });

        // TO header
        doc.setFontSize(9);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(100, 100, 100);
        doc.text('TO:', margin + contentWidth/2 + 8, yPos + 6);

        // Patient Info
        doc.setFontSize(8);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        const patientInfo = [
            `Patient: ${data.patient_name}`,
            `Email: ${data.patient_email}`,
            `Date: ${data.appointment_date}`,
            `Time: ${data.appointment_time}`,
            `Status: ${data.status.toUpperCase()}`
        ];
        patientInfo.forEach((line, i) => {
            doc.text(line, margin + contentWidth/2 + 8, yPos + 12 + (i * 4.5));
        });

        yPos += 42;

        // Services Table Header with gray background
        doc.setFillColor(235, 235, 235);
        doc.rect(margin, yPos, contentWidth, 8, 'F');
        
        doc.setFont(undefined, 'bold');
        doc.setFontSize(8);
        doc.setTextColor(80, 80, 80);
        doc.text('DESCRIPTION', margin + 2, yPos + 5.5);
        doc.text('DURATION', margin + contentWidth - 45, yPos + 5.5);
        doc.text('AMOUNT', margin + contentWidth - 2, yPos + 5.5, { align: 'right' });

        yPos += 10;

        // Convert prices to numbers
        const price = parseFloat(data.price) || 0;
        const downPayment = parseFloat(data.down_payment) || 0;
        
        // Service Item
        doc.setFont(undefined, 'normal');
        doc.setFontSize(9);
        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        doc.text(data.procedure, margin + 2, yPos + 4);
        doc.text(`${data.duration} hours mins`, margin + contentWidth - 45, yPos + 4);
        doc.text(`₱ ${price.toFixed(2)}`, margin + contentWidth - 2, yPos + 4, { align: 'right' });

        yPos += 10;
        doc.setDrawColor(220, 220, 220);
        doc.setLineWidth(0.3);
        doc.line(margin, yPos, pageWidth - margin, yPos);

        // Payment Summary - Right aligned
        yPos += 8;
        doc.setFontSize(9);
        
        const summaryX = pageWidth - margin - 60;
        
        // Subtotal
        doc.setFont(undefined, 'normal');
        doc.text('Subtotal:', summaryX, yPos);
        doc.text(`₱ ${price.toFixed(2)}`, pageWidth - margin, yPos, { align: 'right' });
        yPos += 6;
        
        // Down Payment
        doc.text('Down Payment (20%):', summaryX, yPos);
        doc.text(`- ₱ ${downPayment.toFixed(2)}`, pageWidth - margin, yPos, { align: 'right' });
        yPos += 8;

        // Balance Due - Blue highlighted box
        const balanceDue = price - downPayment;
        doc.setFillColor(37, 99, 235);
        doc.rect(summaryX - 2, yPos - 5, 62, 9, 'F');
        
        doc.setTextColor(255, 255, 255);
        doc.setFont(undefined, 'bold');
        doc.setFontSize(10);
        doc.text('BALANCE DUE:', summaryX, yPos);
        doc.text(`₱ ${balanceDue.toFixed(0)}`, pageWidth - margin, yPos, { align: 'right' });

        // Payment Info Box with border
        yPos += 16;
        doc.setDrawColor(200, 200, 200);
        doc.setLineWidth(0.5);
        doc.rect(margin, yPos, contentWidth, 22);

        doc.setTextColor(textColor[0], textColor[1], textColor[2]);
        doc.setFont(undefined, 'bold');
        doc.setFontSize(9);
        doc.text('PAYMENT INFORMATION', margin + 3, yPos + 6);

        doc.setFont(undefined, 'normal');
        doc.setFontSize(8);
        yPos += 11;
        
        const paymentInfo = [
            `Method: ${data.payment_method || 'gcash'}`,
            `Reference: ${data.payment_reference || 'N/A'}`,
            `Status: ${(data.payment_status || 'PAID').toUpperCase()}`
        ];

        paymentInfo.forEach((info, i) => {
            doc.text(info, margin + 3, yPos + (i * 4.5));
        });

        // Footer with separator line
        yPos = pageHeight - 25;
        doc.setLineWidth(0.3);
        doc.setDrawColor(200, 200, 200);
        doc.line(margin, yPos, pageWidth - margin, yPos);

        yPos += 5;
        doc.setTextColor(grayColor[0], grayColor[1], grayColor[2]);
        doc.setFontSize(8);
        doc.setFont(undefined, 'normal');
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
        if (evt && evt.target) {
            const btn = evt.target.closest('a');
            if (btn) {
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Download Invoice';
                btn.disabled = false;
            }
        }
    }
}
</script>
@endonce

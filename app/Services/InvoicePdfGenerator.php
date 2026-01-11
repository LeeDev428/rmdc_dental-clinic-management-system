<?php

namespace App\Services;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfGenerator
{
    /**
     * Generate PDF invoice for an appointment
     *
     * @param Appointment $appointment
     * @return \Barryvdh\DomPDF\PDF
     */
    public static function generate(Appointment $appointment)
    {
        $pdf = Pdf::loadView('invoices.invoice-template', [
            'appointment' => $appointment->load('user')
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate and save PDF to storage
     *
     * @param Appointment $appointment
     * @param string|null $path
     * @return string Path to saved PDF
     */
    public static function generateAndSave(Appointment $appointment, ?string $path = null)
    {
        $pdf = self::generate($appointment);
        
        $filename = 'invoice_' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        $path = $path ?? storage_path('app/temp/' . $filename);

        // Ensure directory exists
        $directory = dirname($path);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf->save($path);

        return $path;
    }

    /**
     * Generate PDF and return as string
     *
     * @param Appointment $appointment
     * @return string
     */
    public static function generateAsString(Appointment $appointment)
    {
        $pdf = self::generate($appointment);
        return $pdf->output();
    }

    /**
     * Get filename for invoice
     *
     * @param Appointment $appointment
     * @return string
     */
    public static function getFilename(Appointment $appointment)
    {
        return 'Invoice_' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT) . '.pdf';
    }
}

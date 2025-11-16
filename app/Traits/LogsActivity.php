<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Log an activity
     */
    protected function logActivity($type, $description, $data = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data ? json_encode($data) : null,
        ]);
    }

    /**
     * Log appointment activity
     */
    protected function logAppointmentActivity($action, $appointment, $additionalData = [])
    {
        // If appointment is null (e.g., during initiation), use only additionalData
        if ($appointment === null) {
            $data = $additionalData;
            $description = ucfirst($action) . ' appointment for ' . (auth()->user()->name ?? 'Unknown');
        } else {
            $data = array_merge([
                'appointment_id' => $appointment->id,
                'patient_name' => $appointment->user->name ?? 'Unknown',
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'status' => $appointment->status,
            ], $additionalData);
            
            $description = ucfirst($action) . ' appointment for ' . ($appointment->user->name ?? 'Unknown');
        }

        $this->logActivity(
            'appointment_' . $action,
            $description,
            $data
        );
    }

    /**
     * Log patient activity
     */
    protected function logPatientActivity($action, $patient, $additionalData = [])
    {
        $data = array_merge([
            'patient_id' => $patient->id,
            'patient_name' => $patient->name,
            'email' => $patient->email,
        ], $additionalData);

        $this->logActivity(
            'patient_' . $action,
            ucfirst($action) . ' patient: ' . $patient->name,
            $data
        );
    }

    /**
     * Log dental record activity
     */
    protected function logDentalRecordActivity($action, $record, $additionalData = [])
    {
        $data = array_merge([
            'record_id' => $record->id,
            'patient_name' => $record->user->name ?? 'Unknown',
            'tooth_number' => $record->tooth_number ?? null,
            'procedure' => $record->procedure ?? null,
        ], $additionalData);

        $this->logActivity(
            'dental_record_' . $action,
            ucfirst($action) . ' dental record for ' . ($record->user->name ?? 'Unknown'),
            $data
        );
    }

    /**
     * Log payment activity
     */
    protected function logPaymentActivity($action, $payment, $additionalData = [])
    {
        $data = array_merge([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method ?? null,
            'patient_name' => $payment->appointment->user->name ?? 'Unknown',
        ], $additionalData);

        $this->logActivity(
            'payment_' . $action,
            ucfirst($action) . ' payment of ₱' . number_format($payment->amount, 2),
            $data
        );
    }
}

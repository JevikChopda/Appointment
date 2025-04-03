<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
        ]);

        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient record not found.');
        }

        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'Doctor is already booked at this time.');
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'Pending',
        ]);

        if ($appointment) {
            return redirect()->back()->with('success', 'Appointment booked successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to book appointment.');
        }
    }

}

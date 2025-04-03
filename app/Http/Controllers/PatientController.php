<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(){
        $user = Auth::user();
        $patient = Patient::where('user_id',$user->id)->first();

        if(!$patient){
            return redirect()->route('register')->with('error', 'Please register as a patient first.');
        }

        $appointments = Appointment::where('patient_id', $patient->id)->get();
        $doctors = Doctor::all();

        return view('patient.dashboard', compact('patient', 'appointments', 'doctors'));
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
        ]);

        $patient = Patient::where('user_id', Auth::id())->first();

        $conflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'Doctor is already booked at this time.');
        }

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Appointment booked successfully!');
    }
    public function updateAppointment(Request $request, Appointment $appointment)
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required',
        ]);

        if ($appointment->patient_id !== Auth::user()->patient->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $appointment->update([
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Appointment updated successfully!');
    }
    public function cancelAppointment(Appointment $appointment)
    {
        if ($appointment->patient_id !== Auth::user()->patient->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $appointment->delete();

        return redirect()->back()->with('success', 'Appointment canceled successfully.');
    }
}

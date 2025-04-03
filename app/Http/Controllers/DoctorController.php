<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = Auth::id();

        $patientName = $request->input('patient_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $appointments = Appointment::where('doctor_id', $doctorId)
            ->when($patientName, function ($query) use ($patientName) {
                return $query->whereHas('patient', function ($q) use ($patientName) {
                    $q->where('name', 'like', "%$patientName%");
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->where('date', '<=', $endDate);
            })
            ->get();

        return view('doctor.dashboard', compact('appointments'));
    }

    public function acceptAppointment(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $appointment->update(['status' => 'Accepted']);

        return redirect()->back()->with('success', 'Appointment accepted.');
    }

    public function rejectAppointment(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $appointment->update(['status' => 'Pending']);

        return redirect()->back()->with('success', 'Appointment rejected.');
    }

    public function uploadMedicineList(Request $request, Appointment $appointment)
    {
        $request->validate([
            'medicine_list' => 'required|mimes:pdf,txt|max:2048',
        ]);

        if ($appointment->doctor_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($appointment->status !== 'Accepted') {
            return redirect()->back()->with('error', 'Only accepted appointments can have medicine lists.');
        }

        if ($appointment->medicine_list) {
            Storage::delete($appointment->medicine_list);
        }

        $path = $request->file('medicine_list')->store('medicine_lists');

        $appointment->update(['medicine_list' => $path]);

        return redirect()->back()->with('success', 'Medicine list uploaded.');
    }
}

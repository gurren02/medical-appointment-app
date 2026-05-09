<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAppointmentNotifications;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.appointments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:1000',
        ]);

        //Calcular duracion en minutos
        $start = \Carbon\Carbon::createFromFormat('H:i', $data['start_time']);
        $end = \Carbon\Carbon::createFromFormat('H:i', $data['end_time']);
        $data['duration'] = $start->diffInMinutes($end);

        $appointment = Appointment::create($data);

        SendAppointmentNotifications::dispatch($appointment);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita creada',
            'text' => 'La cita ha sido registrada correctamente'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:1000',
            'status' => 'required|in:1,2,3',
        ]);

        //Calcular duracion en minutos
        $start = \Carbon\Carbon::createFromFormat('H:i', $data['start_time']);
        $end = \Carbon\Carbon::createFromFormat('H:i', $data['end_time']);
        $data['duration'] = $start->diffInMinutes($end);

        $appointment->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita actualizada',
            'text' => 'La cita ha sido actualizada correctamente'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita eliminada',
            'text' => 'La cita ha sido eliminada correctamente'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Show the consultation view for an appointment.
     */
    public function consultation(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user', 'consultation.prescriptions']);
        return view('admin.appointments.consultation', compact('appointment'));
    }
}

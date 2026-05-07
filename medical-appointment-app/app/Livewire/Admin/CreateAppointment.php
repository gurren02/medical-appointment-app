<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Livewire\Component;
use Carbon\Carbon;

class CreateAppointment extends Component
{
    // Search fields
    public $searchDate;
    public $searchTime;
    public $searchSpecialty;

    // Results
    public $doctors = [];

    // Selected appointment details
    public $selectedDoctorId;
    public $selectedDoctorName;
    public $selectedDate;
    public $selectedTime;
    public $selectedDuration = 15;
    
    // Form fields
    public $patientId;
    public $reason;

    public function mount()
    {
        $this->searchDate = Carbon::today()->format('Y-m-d');
    }

    public function search()
    {
        $query = Doctor::with('user');

        if ($this->searchSpecialty) {
            $query->where('specialty', 'like', '%' . $this->searchSpecialty . '%');
        }

        // In a real app, we would check doctor_schedules here.
        // For now, we'll return doctors that match the specialty.
        $this->doctors = $query->get();

        // Reset selection when searching
        $this->selectedDoctorId = null;
        $this->selectedTime = null;
    }

    public function selectSlot($doctorId, $doctorName, $time)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedDoctorName = $doctorName;
        $this->selectedDate = $this->searchDate;
        $this->selectedTime = $time;
    }

    public function save()
    {
        $this->validate([
            'selectedDoctorId' => 'required',
            'selectedDate' => 'required|date|after_or_equal:today',
            'selectedTime' => 'required',
            'patientId' => 'required|exists:patients,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $startTime = Carbon::parse($this->selectedTime);
        $endTime = $startTime->copy()->addMinutes($this->selectedDuration);

        Appointment::create([
            'doctor_id' => $this->selectedDoctorId,
            'patient_id' => $this->patientId,
            'date' => $this->selectedDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'duration' => $this->selectedDuration,
            'reason' => $this->reason,
            'status' => 1, // Programado
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita confirmada',
            'text' => 'La cita ha sido registrada exitosamente'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $patients = Patient::with('user')->get();
        // Get unique specialties for the select
        $specialties = Doctor::whereNotNull('specialty')->distinct()->pluck('specialty');

        return view('livewire.admin.create-appointment', compact('patients', 'specialties'));
    }
}

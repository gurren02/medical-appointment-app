<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Livewire\Component;

class ScheduleManager extends Component
{
    public $doctor;
    public $days = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES'];
    public $hours = ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00'];
    public $intervals = ['00:00', '15:00', '30:00', '45:00'];
    
    public $selectedSlots = []; // Format: ['LUNES-08:00:00-08:15:00' => true]

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $schedules = DoctorSchedule::where('doctor_id', $this->doctor->id)->get();
        foreach ($schedules as $schedule) {
            $key = "{$schedule->day}-{$schedule->start_time}-{$schedule->end_time}";
            $this->selectedSlots[$key] = true;
        }
    }

    public function save()
    {
        DoctorSchedule::where('doctor_id', $this->doctor->id)->delete();

        foreach ($this->selectedSlots as $key => $selected) {
            if ($selected) {
                $parts = explode('-', $key);
                if (count($parts) === 3) {
                    DoctorSchedule::create([
                        'doctor_id' => $this->doctor->id,
                        'day' => $parts[0],
                        'start_time' => $parts[1],
                        'end_time' => $parts[2],
                    ]);
                }
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Horario actualizado',
            'text' => 'El horario del doctor ha sido actualizado correctamente'
        ]);

        return redirect()->route('admin.doctors.schedules', $this->doctor);
    }

    public function toggleAllDay($day, $hour, $value)
    {
        foreach ($this->intervals as $i => $start) {
            $endHour = $hour;
            $startOffset = substr($start, 0, 5);
            $endOffset = ($i < 3) ? substr($this->intervals[$i+1], 0, 5) : '00';
            
            $startTime = substr($hour, 0, 3) . $startOffset;
            $endTime = ($i < 3) ? substr($hour, 0, 3) . $endOffset : sprintf("%02d:00", intval(substr($hour, 0, 2)) + 1);
            
            $key = "{$day}-{$startTime}:00-{$endTime}:00";
            $this->selectedSlots[$key] = $value;
        }
    }

    public function render()
    {
        return view('livewire.admin.schedule-manager');
    }
}

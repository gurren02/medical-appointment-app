<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Consultation;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    //Pestaña activa
    public $activeTab = 'consulta';

    //Campos de consulta
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';

    //Medicamentos (receta)
    public $medications = [];

    //Modales
    public $showMedicalHistoryModal = false;
    public $showPastConsultationsModal = false;
    public $pastConsultations = [];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load(['patient.user', 'patient.bloodType', 'doctor.user', 'consultation.prescriptions']);

        //Si ya existe una consulta, cargar los datos
        if ($this->appointment->consultation) {
            $this->diagnosis = $this->appointment->consultation->diagnosis ?? '';
            $this->treatment = $this->appointment->consultation->treatment ?? '';
            $this->notes = $this->appointment->consultation->notes ?? '';

            //Cargar medicamentos existentes
            if ($this->appointment->consultation->prescriptions) {
                foreach ($this->appointment->consultation->prescriptions as $prescription) {
                    $this->medications[] = [
                        'medication' => $prescription->medication,
                        'dose' => $prescription->dose,
                        'frequency' => $prescription->frequency,
                    ];
                }
            }
        }
    }

    //Añadir medicamento
    public function addMedication()
    {
        $this->medications[] = [
            'medication' => '',
            'dose' => '',
            'frequency' => '',
        ];
    }

    //Eliminar medicamento
    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    //Abrir modal de historia médica (tipo sangre, alergias, etc)
    public function openMedicalHistoryModal()
    {
        $this->showMedicalHistoryModal = true;
    }

    public function closeMedicalHistoryModal()
    {
        $this->showMedicalHistoryModal = false;
    }

    //Abrir modal de consultas anteriores (lista de visitas)
    public function openPastConsultationsModal()
    {
        $patientId = $this->appointment->patient_id;

        $this->pastConsultations = Consultation::whereHas('appointment', function ($query) use ($patientId) {
            $query->where('patient_id', $patientId)
                  ->where('id', '!=', $this->appointment->id);
        })
        ->with(['appointment.doctor.user'])
        ->orderBy('created_at', 'desc')
        ->get();

        $this->showPastConsultationsModal = true;
    }

    public function closePastConsultationsModal()
    {
        $this->showPastConsultationsModal = false;
    }

    //Guardar consulta
    public function save()
    {
        $this->validate([
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'medications.*.medication' => 'nullable|string|max:255',
            'medications.*.dose' => 'nullable|string|max:255',
            'medications.*.frequency' => 'nullable|string|max:255',
        ]);

        //Crear o actualizar la consulta
        $consultation = Consultation::updateOrCreate(
            ['appointment_id' => $this->appointment->id],
            [
                'diagnosis' => $this->diagnosis,
                'treatment' => $this->treatment,
                'notes' => $this->notes,
            ]
        );

        //Eliminar recetas anteriores y crear nuevas
        $consultation->prescriptions()->delete();

        foreach ($this->medications as $med) {
            if (!empty($med['medication'])) {
                $consultation->prescriptions()->create([
                    'medication' => $med['medication'],
                    'dose' => $med['dose'] ?? '',
                    'frequency' => $med['frequency'] ?? '',
                ]);
            }
        }

        //Marcar la cita como completada
        $this->appointment->update(['status' => Appointment::STATUS_COMPLETADO]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Consulta guardada',
            'text' => 'La consulta ha sido guardada correctamente'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}

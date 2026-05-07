<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
    ];

    //Constantes de estado
    const STATUS_PROGRAMADO = 1;
    const STATUS_COMPLETADO = 2;
    const STATUS_CANCELADO = 3;

    //Mapeo de estados
    public static $statusLabels = [
        self::STATUS_PROGRAMADO => 'Programado',
        self::STATUS_COMPLETADO => 'Completado',
        self::STATUS_CANCELADO => 'Cancelado',
    ];

    //Accessor para obtener el texto del estado
    public function getStatusLabelAttribute()
    {
        return self::$statusLabels[$this->status] ?? 'Desconocido';
    }

    //Relacion con paciente
    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    //Relacion con doctor
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    //Relacion con consulta
    public function consultation(){
        return $this->hasOne(Consultation::class);
    }
}

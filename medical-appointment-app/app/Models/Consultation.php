<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'treatment',
        'notes',
    ];

    //Relacion con cita
    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    //Relacion con recetas
    public function prescriptions(){
        return $this->hasMany(Prescription::class);
    }
}

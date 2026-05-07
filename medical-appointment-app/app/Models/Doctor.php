<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
    ];

    //Relacion uno a uno inversa
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Relacion uno a muchos con citas
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    //Relacion uno a muchos con horarios
    public function schedules(){
        return $this->hasMany(DoctorSchedule::class);
    }
}

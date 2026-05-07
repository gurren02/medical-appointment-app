<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener usuarios que tienen el rol de Doctor pero que aún no están en la tabla de doctores
        // O simplemente todos los usuarios para seleccionar uno (siguiendo el patrón de UserController)
        // En UserController, si es paciente se crea el registro. Aquí haremos algo similar o manual.
        $users = User::role('Doctor')->whereDoesntHave('doctor')->get();
        return view('admin.doctors.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctors,user_id',
            'specialty' => 'nullable|string|max:255',
        ]);

        Doctor::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Doctor creado',
            'text' => 'El doctor ha sido registrado correctamente'
        ]);

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'specialty' => 'nullable|string|max:255',
        ]);

        $doctor->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Doctor actualizado',
            'text' => 'La información del doctor ha sido actualizada correctamente'
        ]);

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Doctor eliminado',
            'text' => 'El registro de doctor ha sido eliminado correctamente'
        ]);

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Show the schedule management view.
     */
    public function schedules(Doctor $doctor)
    {
        return view('admin.doctors.schedules', compact('doctor'));
    }
}

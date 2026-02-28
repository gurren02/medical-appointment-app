<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Muestra la lista de roles.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Guarda un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        // 2. Creación usando el Modelo directamente (Corregido $rol por Role)
        Role::create([
            'name' => $request->name
        ]);

        // 3. Notificación con SweetAlert (Flash session)
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol creado con éxito',
            'text' => 'El rol ha sido creado con éxito'
        ]);

        // 4. Redirección usando el nombre de la ruta (Corregido)
        return redirect(route('admin.roles.index'))->with('success', 'Role created successfully');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Role $role)
    {
        // Corregido: compact recibe un string 'role', no la variable $role
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Actualiza el rol en la base de datos.
     */
public function update(Request $request, Role $role)
{
    // 1. Validar (exceptuando el ID actual para el nombre único)
    $request->validate([
        'name' => "required|unique:roles,name,{$role->id}"
    ]);

    // 2. Actualizar
    $role->update([
        'name' => $request->name
    ]);

    // 3. Redirigir al INDEX (sin parámetros faltantes)
    return redirect()->route('admin.roles.index')->with('swal', [
        'icon' => 'success',
        'title' => 'Rol actualizado',
        'text' => 'El nombre del rol se cambió con éxito.'
    ]);
}

    /**
     * Elimina un rol.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        session()->flash('swal',[
            'icon' => 'success',
            'title' => 'Rol eliminado',
            'text' => 'El rol se eliminó correctamente'
        ]);

        return redirect()->route('admin.roles.index')->with('info', 'El rol se eliminó correctamente');
    }
}
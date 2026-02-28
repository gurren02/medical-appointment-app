<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Permission\Models\Role;

class RoleTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->sortable(),
            Column::make("Fecha", "created_at")
                ->sortable()
                ->format(function($value){
                    return $value->format('d/m/Y');
                }),
            // 1. Corregimos 'Column'
            Column::make("Acciones")
                // 2. Acomodamos el paréntesis y la llave aquí
                ->label(function($row) { 
                    return view('admin.roles.actions', ['role' => $row]);
                }) // Y cerramos el paréntesis del label aquí
        ];
    }
    
}

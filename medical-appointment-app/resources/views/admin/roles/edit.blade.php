<x-admin-layout title="Roles" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Roles',
        'href'=> route('admin.roles.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">
    <x-wire-card title="Editar Rol">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-wire-input 
                    label="Nombre" 
                    name="name" 
                    placeholder="Nombre del rol" 
                    value="{{ old('name', $role->name) }}">
                </x-wire-input>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>
                    Actualizar Rol
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
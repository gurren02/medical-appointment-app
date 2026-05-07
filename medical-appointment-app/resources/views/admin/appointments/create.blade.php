<x-admin-layout title="Citas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Nuevo',
    ]
]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Nueva cita</h1>
        <p class="text-gray-500">Gestione la creación de citas médicas con búsqueda de disponibilidad.</p>
    </div>

    @livewire('admin.create-appointment')
</x-admin-layout>

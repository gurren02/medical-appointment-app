<x-admin-layout title="Horarios" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Horarios',
    ]
]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Horarios de {{ $doctor->user->name }}</h1>
        <p class="text-gray-500">Gestione la disponibilidad para citas médicas.</p>
    </div>

    @livewire('admin.schedule-manager', ['doctor' => $doctor])

</x-admin-layout>

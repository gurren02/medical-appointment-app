<x-admin-layout title="Doctores" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
    ]
]">

    <x-slot name="action">
        <x-wire-button blue href="{{route('admin.doctors.create')}}" xs>
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.doctor-table')
</x-admin-layout>

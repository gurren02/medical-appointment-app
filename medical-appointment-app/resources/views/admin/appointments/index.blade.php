<x-admin-layout title="Citas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
    ]
]">

    <x-slot name="action">
        <x-wire-button blue href="{{route('admin.appointments.create')}}" xs>
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.appointment-table')
</x-admin-layout>

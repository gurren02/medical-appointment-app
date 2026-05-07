<x-admin-layout title="Doctores" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">

    <x-wire-card>
        <x-validation-errors class="mb-4"/>
        <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <span class="text-gray-500 font-semibold">Doctor:</span>
                    <span class="text-gray-900 ml-1">{{ $doctor->user->name }}</span>
                </div>

                <x-wire-input label="Especialidad" name="specialty" placeholder="Ej. Cardiología" value="{{ old('specialty', $doctor->specialty) }}"></x-wire-input>

                <div class="flex justify-end gap-4">
                    <x-wire-button outline gray href="{{route('admin.doctors.index')}}">Volver</x-wire-button>
                    <x-wire-button type="submit" blue>Guardar cambios</x-wire-button>
                </div>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

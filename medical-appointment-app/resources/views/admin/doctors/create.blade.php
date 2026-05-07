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
        'name' => 'Nuevo',
    ]
]">

    <x-wire-card>
        <x-validation-errors class="mb-4"/>
        <form action="{{route('admin.doctors.store')}}" method="POST">
            @csrf
            <div class="space-y-4">
                <x-wire-native-select name="user_id" label="Usuario" required>
                    <option value="">Seleccione un usuario</option>
                    @foreach ($users as $user)
                        <option value="{{$user->id}}" @selected(old('user_id') == $user->id)>
                            {{$user->name}} ({{$user->email}})
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input label="Especialidad" name="specialty" placeholder="Ej. Cardiología" value="{{ old('specialty') }}"></x-wire-input>

                <div class="flex justify-end">
                    <x-wire-button type="submit" blue>Guardar</x-wire-button>
                </div>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

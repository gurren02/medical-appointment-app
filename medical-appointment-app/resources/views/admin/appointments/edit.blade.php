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
        'name' => 'Editar',
    ]
]">

    <x-wire-card>
        <x-validation-errors class="mb-4"/>
        <form action="{{route('admin.appointments.update', $appointment)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    {{-- Seleccion de paciente --}}
                    <x-wire-native-select name="patient_id" label="Paciente" required>
                        <option value="">Seleccione un paciente</option>
                        @foreach ($patients as $patient)
                            <option value="{{$patient->id}}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                                {{$patient->user->name}}
                            </option>
                        @endforeach
                    </x-wire-native-select>

                    {{-- Seleccion de doctor --}}
                    <x-wire-native-select name="doctor_id" label="Doctor" required>
                        <option value="">Seleccione un doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{$doctor->id}}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                                {{$doctor->user->name}} - {{$doctor->specialty ?? 'General'}}
                            </option>
                        @endforeach
                    </x-wire-native-select>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    {{-- Fecha --}}
                    <div>
                        <label for="date" class="block mb-2 text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="date" id="date"
                            value="{{ old('date', $appointment->date) }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hora de inicio --}}
                    <div>
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-700">Hora de inicio</label>
                        <input type="time" name="start_time" id="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hora de fin --}}
                    <div>
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-700">Hora de fin</label>
                        <input type="time" name="end_time" id="end_time"
                            value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i')) }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Estado --}}
                <x-wire-native-select name="status" label="Estado" required>
                    <option value="1" @selected(old('status', $appointment->status) == 1)>Programado</option>
                    <option value="2" @selected(old('status', $appointment->status) == 2)>Completado</option>
                    <option value="3" @selected(old('status', $appointment->status) == 3)>Cancelado</option>
                </x-wire-native-select>

                {{-- Motivo de la cita --}}
                <x-wire-textarea label="Motivo de la cita" name="reason" placeholder="Describa el motivo de la cita...">
                    {{ old('reason', $appointment->reason) }}
                </x-wire-textarea>

                <div class="flex justify-end gap-4">
                    <x-wire-button outline gray href="{{route('admin.appointments.index')}}">Volver</x-wire-button>
                    <x-wire-button type="submit" blue>
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

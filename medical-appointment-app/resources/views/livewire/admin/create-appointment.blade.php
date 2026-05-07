<div>
    {{-- Card de Búsqueda (Ancho Total) --}}
    <x-wire-card class="mb-6">
        <div class="space-y-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Buscar disponibilidad</h2>
                <p class="text-sm text-gray-500">Encuentra el horario perfecto para tu cita.</p>
            </div>
            
            <div class="grid grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                    <input type="date" wire:model="searchDate" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hora</label>
                    <select wire:model="searchTime" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Cualquier hora</option>
                        <option value="08:00:00-09:00:00">08:00:00 - 09:00:00</option>
                        <option value="09:00:00-10:00:00">09:00:00 - 10:00:00</option>
                        <option value="10:00:00-11:00:00">10:00:00 - 11:00:00</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Especialidad (opcional)</label>
                    <select wire:model="searchSpecialty" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Todas las especialidades</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}">{{ $specialty }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-wire-button blue wire:click="search" class="w-full py-2.5">
                        Buscar disponibilidad
                    </x-wire-button>
                </div>
            </div>
        </div>
    </x-wire-card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Columna Izquierda: Resultados (2/3) --}}
        <div class="md:col-span-2 space-y-6">

        {{-- Resultados de Doctores --}}
        <div class="space-y-4">
            @forelse($doctors as $doctor)
                <x-wire-card>
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                            {{ collect(explode(' ', $doctor->user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('') }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $doctor->user->name }}</h3>
                            <p class="text-sm text-indigo-600 font-medium">{{ $doctor->specialty }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Horarios disponibles:</p>
                        <div class="flex flex-wrap gap-2">
                            {{-- Simulación de slots --}}
                            @foreach(['08:00:00', '08:15:00', '08:30:00', '08:45:00'] as $time)
                                <button wire:click="selectSlot({{ $doctor->id }}, '{{ $doctor->user->name }}', '{{ $time }}')"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ ($selectedDoctorId == $doctor->id && $selectedTime == $time) ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                                    {{ substr($time, 0, 5) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </x-wire-card>
            @empty
                @if($searchDate)
                    <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-500">No se encontraron doctores disponibles para los criterios seleccionados.</p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>

    {{-- Columna Derecha: Resumen (1/3) --}}
    <div class="md:col-span-1 space-y-6">
        <x-wire-card>
            <div class="space-y-6">
                <h2 class="text-xl font-bold text-gray-800">Resumen de la cita</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Doctor:</span>
                        <span class="text-gray-900 font-bold">{{ $selectedDoctorName ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Fecha:</span>
                        <span class="text-gray-900 font-bold">{{ $selectedDate ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Horario:</span>
                        <span class="text-gray-900 font-bold">
                            @if($selectedTime)
                                {{ substr($selectedTime, 0, 5) }} - {{ Carbon\Carbon::parse($selectedTime)->addMinutes($selectedDuration)->format('H:i') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Duración:</span>
                        <span class="text-gray-900 font-bold">{{ $selectedDuration }} minutos</span>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Paciente</label>
                        <select wire:model="patientId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Seleccione un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                            @endforeach
                        </select>
                        @error('patientId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Motivo de la cita</label>
                        <textarea wire:model="reason" rows="4" 
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                  placeholder="Escriba el motivo aquí..."></textarea>
                        @error('reason') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <x-wire-button blue class="w-full py-3 text-lg font-bold" wire:click="save" :disabled="!$selectedDoctorId">
                        Confirmar cita
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>
    </div>
</div>

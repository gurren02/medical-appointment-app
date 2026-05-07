<div>
    {{-- Header del paciente --}}
    <x-wire-card class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $appointment->patient->user->name }}</h2>
                <p class="text-gray-500">DNI: {{ $appointment->patient->user->id_number }}</p>
            </div>
            <div class="flex gap-3">
                <x-wire-button outline gray wire:click="openMedicalHistoryModal">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i>
                    Ver Historia
                </x-wire-button>
                <x-wire-button outline gray wire:click="openPastConsultationsModal">
                    <i class="fa-solid fa-notes-medical mr-2"></i>
                    Consultas Anteriores
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    {{-- Tabs de Consulta y Receta --}}
    <x-wire-card>
        <x-tabs :active="$activeTab">
            <x-slot name="header">
                <x-tabs-link tab="consulta">
                    <i class="fa-solid fa-stethoscope me-2"></i>
                    Consulta
                </x-tabs-link>
                <x-tabs-link tab="receta">
                    <i class="fa-solid fa-prescription me-2"></i>
                    Receta
                </x-tabs-link>
            </x-slot>

            {{-- Tab Consulta --}}
            <x-tab-content tab="consulta">
                <div class="space-y-4">
                    <div>
                        <label for="diagnosis" class="block mb-2 text-sm font-medium text-gray-700">Diagnóstico</label>
                        <textarea wire:model="diagnosis" id="diagnosis" rows="4"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                        @error('diagnosis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="treatment" class="block mb-2 text-sm font-medium text-gray-700">Tratamiento</label>
                        <textarea wire:model="treatment" id="treatment" rows="4"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                        @error('treatment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Notas</label>
                        <textarea wire:model="notes" id="notes" rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-tab-content>

            {{-- Tab Receta --}}
            <x-tab-content tab="receta">
                <div class="space-y-4">
                    @foreach ($medications as $index => $medication)
                        <div class="flex flex-row gap-4 items-end p-4 bg-gray-50 rounded-lg border border-gray-200 w-full">
                            <div class="basis-2/5 flex-grow">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Medicamento</label>
                                <input type="text" wire:model="medications.{{ $index }}.medication"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    placeholder="Ej. Amoxicilina 500mg">
                            </div>
                            <div class="basis-1/5 flex-grow">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Dosis</label>
                                <input type="text" wire:model="medications.{{ $index }}.dose"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    placeholder="Ej. 1 cada 8 horas">
                            </div>
                            <div class="basis-2/5 flex-grow">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Frecuencia / Duración</label>
                                <input type="text" wire:model="medications.{{ $index }}.frequency"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    placeholder="Ej. cada 8 horas por 7 días">
                            </div>
                            <div class="flex-none">
                                <x-wire-button wire:click="removeMedication({{ $index }})" red xs>
                                    <i class="fa-solid fa-trash"></i>
                                </x-wire-button>
                            </div>
                        </div>
                    @endforeach

                    {{-- Boton para añadir medicamento --}}
                    <x-wire-button wire:click="addMedication" outline gray sm>
                        <i class="fa-solid fa-plus mr-2"></i>
                        Añadir Medicamento
                    </x-wire-button>
                </div>
            </x-tab-content>
        </x-tabs>

        {{-- Boton guardar consulta --}}
        <div class="flex justify-end mt-6 px-4 pb-4">
            <x-wire-button wire:click="save" blue>
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Guardar Consulta
            </x-wire-button>
        </div>
    </x-wire-card>

    {{-- Modal de Historia Médica --}}
    @if($showMedicalHistoryModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Fondo oscuro --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" wire:click="closeMedicalHistoryModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            {{-- Contenido del modal --}}
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            Historia médica del paciente
                        </h3>
                        <button wire:click="closeMedicalHistoryModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        {{-- Tipo de sangre --}}
                        <div>
                            <span class="block text-sm text-gray-500 font-medium mb-1">Tipo de sangre:</span>
                            <span class="text-lg font-bold text-gray-900">{{ $appointment->patient->bloodType->name ?? 'N/R' }}</span>
                        </div>
                        {{-- Alergias --}}
                        <div>
                            <span class="block text-sm text-gray-500 font-medium mb-1">Alergias:</span>
                            <span class="text-lg font-bold text-gray-900">{{ $appointment->patient->allergies ?? 'No registradas' }}</span>
                        </div>
                        {{-- Enfermedades crónicas --}}
                        <div>
                            <span class="block text-sm text-gray-500 font-medium mb-1">Enfermedades crónicas:</span>
                            <span class="text-lg font-bold text-gray-900">{{ $appointment->patient->chronic_conditions ?? 'No registradas' }}</span>
                        </div>
                        {{-- Antecedentes quirúrgicos --}}
                        <div>
                            <span class="block text-sm text-gray-500 font-medium mb-1">Antecedentes quirúrgicos:</span>
                            <span class="text-lg font-bold text-gray-900">{{ $appointment->patient->surgical_history ?? 'No registradas' }}</span>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('admin.patients.edit', $appointment->patient) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm transition-colors">
                            Ver / Editar Historia Médica
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal de Consultas Anteriores --}}
    @if($showPastConsultationsModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Fondo oscuro --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" wire:click="closePastConsultationsModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            {{-- Contenido del modal --}}
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            Consultas Anteriores
                        </h3>
                        <button wire:click="closePastConsultationsModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    @if(count($pastConsultations) > 0)
                        <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                            @foreach($pastConsultations as $consultation)
                                <div class="bg-white rounded-xl p-5 border border-indigo-100 shadow-sm hover:shadow-md transition-shadow relative group">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                                <i class="fa-solid fa-calendar-day"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ \Carbon\Carbon::parse($consultation->appointment->date)->format('d/m/Y') }} a las {{ substr($consultation->appointment->start_time, 0, 5) }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Atendido por: <span class="font-medium text-indigo-600">{{ $consultation->appointment->doctor->user->name ?? 'N/A' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <x-wire-button outline indigo xs label="Consultar Detalle" />
                                    </div>

                                    <div class="space-y-3 bg-gray-50 rounded-lg p-4">
                                        @if($consultation->diagnosis)
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Diagnóstico:</p>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ $consultation->diagnosis }}</p>
                                            </div>
                                        @endif
                                        @if($consultation->treatment)
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tratamiento:</p>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ Str::limit($consultation->treatment, 150) }}</p>
                                            </div>
                                        @endif
                                        @if($consultation->notes)
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Notas:</p>
                                                <p class="text-sm text-gray-700 leading-relaxed italic">"{{ $consultation->notes }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No hay consultas anteriores registradas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

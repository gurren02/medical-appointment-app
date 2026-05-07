<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Gestor de horarios</h2>
        <x-wire-button blue wire:click="save">
            Guardar horario
        </x-wire-button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200">DÍA/HORA</th>
                    @foreach($days as $day)
                        <th class="px-4 py-3 text-left font-bold text-gray-500 uppercase tracking-wider">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($hours as $hour)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <x-wire-checkbox id="check-all-{{ $hour }}" />
                                <span>{{ $hour }}</span>
                            </div>
                        </td>
                        @foreach($days as $day)
                            <td class="px-4 py-4 align-top">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                               wire:click="toggleAllDay('{{ $day }}', '{{ $hour }}', $event.target.checked)">
                                        <span class="text-xs text-gray-500 font-semibold uppercase">Todos</span>
                                    </div>
                                    <div class="space-y-1">
                                        @php
                                            $baseHour = intval(substr($hour, 0, 2));
                                        @endphp
                                        @foreach(['00', '15', '30', '45'] as $i => $min)
                                            @php
                                                $start = sprintf("%02d:%s:00", $baseHour, $min);
                                                $endMin = ($i < 3) ? (intval($min) + 15) : '00';
                                                $endHour = ($i < 3) ? $baseHour : ($baseHour + 1);
                                                $end = sprintf("%02d:%02d:00", $endHour, $endMin);
                                                $key = "{$day}-{$start}-{$end}";
                                            @endphp
                                            <div class="flex items-center gap-2 pl-2">
                                                <input type="checkbox" 
                                                       wire:model="selectedSlots.{{ $key }}"
                                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-3 w-3">
                                                <span class="text-xs text-gray-600">{{ substr($start, 0, 5) }} - {{ substr($end, 0, 5) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-admin-layout title="Pacientes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pacientes',
        'href'=> route('admin.patients.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">

        <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')
            <x-wire-card>
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="{{$patient->user ->profile_photo_url}}" alt="{{$patient->user->name}}" class="h-20 w-20 rounded-full object-cover object-center">
                        <div class="ml-4">
                            <p class="text-2x1 font-bold text-gray-900">{{$patient->user->name}}</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <x-wire-button outline gray href="{{route('admin.patients.index')}}">Volver</x-wire-button>
                        <x-wire-button type="submit">
                            <i class="fa-solid fa-check mr-2"></i>
                            Guardar cambios
                        </x-wire-button>
                    </div>
                </div>
            </x-wire-card>
            {{-- Tabs de navegacion --}}
            <x-wire-card>
                <div x-data="{tab: 'datos-personales'}">
                    {{-- Menu de pestañas --}}
            <div class="sm:hidden">
                <label for="tabs-icons" class="sr-only">Select your country</label>
                <select id="tabs-icons" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body">
                    <option>Profile</option>
                    <option>Dashboard</option>
                    <option>setting</option>
                    <option>Invoice</option>
                </select>
            </div>
            <ul class="hidden text-sm font-medium text-center text-body sm:flex -space-x-px">
                {{-- Tab 1: Datos personales --}}
                <li class="w-full focus-within:z-10">
                    <a href="#" x-on:click="tab = 'datos-personales'" 
                    :class="{
                        'text-blue-600 active' : tab == 'datos-personales',
                        'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'datos-personales'
                    }"
                    class="inline-flex items-center justify-center w-full text-body bg-neutral-primary-soft border border-default rounded-s-base hover:bg-neutral-secondary-medium hover:text-heading focus:ring-4 focus:ring-neutral-secondary-strong font-medium leading-5 text-sm px-4 py-2.5 focus:outline-none" aria-current="tab =='datos-personales' ? 'page' : undefined" transition-colors duration-200>
                    <i class="fa-solid fa-user me-2"></i>
                        Datos personales
                    </a>
                </li>
                {{-- Tab 2:Antecedentes  --}}
                <li class="w-full focus-within:z-10">
                    <a href="#" x-on:click="tab = 'antecedentes'" 
                    :class="{
                        'text-blue-600 active' : tab == 'antecedentes',
                        'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'antecedentes'
                    }"
                    class="inline-flex items-center justify-center w-full text-body bg-neutral-primary-soft border border-default rounded-s-base hover:bg-neutral-secondary-medium hover:text-heading focus:ring-4 focus:ring-neutral-secondary-strong font-medium leading-5 text-sm px-4 py-2.5 focus:outline-none" aria-current="tab =='antecedentes' ? 'page' : undefined" transition-colors duration-200>
                    <i class="fa-solid fa-file-lines me-2"></i>
                        Antecedentes
                    </a>
                </li>
                {{-- Tab 3:Informacion general  --}}
                <li class="w-full focus-within:z-10">
                    <a href="#" x-on:click="tab = 'informacion-general'" 
                    :class="{
                        'text-blue-600 active' : tab == 'informacion-general',
                        'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'informacion-general'
                    }"
                    class="inline-flex items-center justify-center w-full text-body bg-neutral-primary-soft border border-default rounded-s-base hover:bg-neutral-secondary-medium hover:text-heading focus:ring-4 focus:ring-neutral-secondary-strong font-medium leading-5 text-sm px-4 py-2.5 focus:outline-none" aria-current="tab =='informacion-general' ? 'page' : undefined" transition-colors duration-200>
                    <i class="fa-solid fa-info me-2"></i>
                        Informacion general
                    </a>
                </li>
                {{-- Tab 4: Contacto de emergencia  --}}
                <li class="w-full focus-within:z-10">
                    <a href="#" x-on:click="tab = 'contacto-emergencia'" 
                    :class="{
                        'text-blue-600 active' : tab == 'contacto-emergencia',
                        'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'contacto-emergencia'
                    }"
                    class="inline-flex items-center justify-center w-full text-body bg-neutral-primary-soft border border-default rounded-s-base hover:bg-neutral-secondary-medium hover:text-heading focus:ring-4 focus:ring-neutral-secondary-strong font-medium leading-5 text-sm px-4 py-2.5 focus:outline-none" aria-current="tab =='contacto-emergencia' ? 'page' : undefined" transition-colors duration-200>
                    <i class="fa-solid fa-heart me-2"></i>
                        Contacto de emergencia
                    </a>
                </li>
            </ul>
            {{-- Contenido de los tabs --}}
            <div class="px-4 mt-4">
                {{-- Tab 1: Datos personales --}}
                <div x-show="tab == 'datos-personales'">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                          {{-- Lado izquierdo--}}  
                          <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-blue-800">Edición de cuenta de usuario</h3>
                                <div class="mt-1 text-sm text-blue-600">
                                    <p><strong>La información de acceso</strong> (nombre, email y contraseña) debe gestionarse desde la cuenta de usuario asociada</p>
                                </div>
                            </div>
                          </div>
                          {{-- Lado derecho --}}
                          <div class="flex-shrink-0 mt-3 sm:mt-0 sm:ml-4">
                              <x-wire-button primary sm href="{{route('admin.users.edit', $patient->user)}}" target="_blank">Editar usuario
                                  <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                              </x-wire-button>
                          </div>
                        </div>
                    </div>
                </div>
                {{-- Tab 2: Antecedentes --}}
                <div x-show="tab == 'antecedentes'">
                    <p>Antecedentes</p>
                </div>
                {{-- Tab 3: Informacion general --}}
                <div x-show="tab == 'informacion-general'">
                    <p>Informacion general</p>
                </div>
                {{-- Tab 4: Contacto de emergencia --}}
                <div x-show="tab == 'contacto-emergencia'">
                    <p>Contacto de emergencia</p>
                </div>
            </div>
            </div>
            </x-wire-card>
        </form>

</x-admin-layout>
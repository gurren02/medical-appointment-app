<div class="flex items-center gap-2">
    <x-wire-button href="{{route('admin.doctors.edit', $doctor)}}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <x-wire-button href="{{route('admin.doctors.schedules', $doctor)}}" green xs>
        <i class="fa-solid fa-clock"></i>
    </x-wire-button>

    <form action="{{ route('admin.doctors.destroy', $doctor)}}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>

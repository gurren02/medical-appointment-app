@props(['active' => 'default'])
<div x-data="{tab: '{{ $active }}'}">
    @isset($header)
        <div class="border-b border-gray-200">
            <ul class="hidden text-sm font-medium text-center text-body sm:flex space-x-4">
                {{ $header }}
            </ul>
        </div>
    @endisset
        <div class="px-4 mt-4">
            {{ $slot }}
        </div>
</div>
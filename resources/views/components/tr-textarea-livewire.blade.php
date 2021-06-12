@props(['title', 'cols' => 48, 'rows' => 5])

<tr>
    <td class="nadpis">{{ $title }}</td>
    <td>
        @if (strlen($slot))
            <x-admin.napoveda>
                {{ $slot }}
            </x-admin.napoveda>
        @endif
    </td>
    <td>
        <textarea
            {{ $attributes->whereStartsWith('wire:model') }}
            cols="{{ $cols }}"
            rows="{{ $rows }}"
        ></textarea>
        @error($attributes->whereStartsWith('wire:model')->first())<div class="validation-error">{{ $message }}</div>@enderror
    </td>
</tr>

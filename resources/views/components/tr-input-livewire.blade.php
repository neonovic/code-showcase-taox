@props(['title', 'size' => 65, 'required', 'maxlength'])

<tr>
    <td class="nadpis">{{ $title }}</td>
    <td>
        @if (strlen($slot))
            <x-admin.napoveda>
                {{ $slot }}
            </x-admin.napoveda>
        @endif
    </td>
    <td colspan="2">
        <input type="text"
               {{ $attributes->whereStartsWith('wire:model') }}
               size="{{ $size }}"
               @if (isset($maxlength)) maxlength="{{ $maxlength }}" @endif
        >
        @if(isset($required))
            <span class="red">*</span>
        @endif
        @error($attributes->whereStartsWith('wire:model')->first())<div class="validation-error">{{ $message }}</div>@enderror
    </td>
</tr>

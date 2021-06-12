@props(['title', 'id', 'entity'])

<tr wire:ignore wire:key="wysiwyg-{{ $id }}">
    <td class="nadpis">{{ $title }}</td>
    <td>
        @if (strlen($slot))
            <x-admin.napoveda>
                {{ $slot }}
            </x-admin.napoveda>
        @endif
    </td>
    <td colspan="2">
        <textarea
            {{ $attributes->whereStartsWith('wire:model') }}
            id="{{ $id }}"
            x-data
            x-init="
                CKEDITOR.replace('{{ $id }}');
                CKEDITOR.instances.{{ $id }}.on('change', function() {
                    $dispatch('input', this.getData());
                });"
        ></textarea>
        @error($attributes->whereStartsWith('wire:model')->first())<div class="validation-error">{{ $message }}</div>@enderror
    </td>
</tr>

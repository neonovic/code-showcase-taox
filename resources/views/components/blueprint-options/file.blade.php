@props(['data', 'key', 'option', 'index'])

<tr>
    <td class="nadpis">
        {{ $data['display'] ?? 'Soubor' }}<br>
        @if (isset($data['suggested']))
            <font size="1">Doporučené rozměry: {{ $data['suggested'] }} px</font>
        @endif
    </td>
    <td>
        @if (isset($data['help']))
            <x-admin.napoveda>
                {{ $data['help'] }}
            </x-admin.napoveda>
        @endif
    </td>
    <td>
        @if (!empty($data['editable']))
            <input type="file" name="option_{{ $key }}{{ $data['multiple'] ? '[]' : '' }}" id="option_{{ $key }}">
        @endif
    </td>
    <td align="right">
        @if((is_int($data['value']) || is_array($data['value']) ) && $option->hasMedia('blueprint-media'))
            @php
                $fileId = is_int($data['value']) ? $data['value'] : $data['value'][$index];
            @endphp

            @if (is_int($fileId))
                <img src="{{ $option->getMediaUrlById($fileId) }}" style="max-width:{{ $data['preview_width'] ?? '360' }}px;" border="0">
                @if (!empty($data['editable']))
                    <a title="vymazat" onClick="return confirm('Opravdu vymazat?');" href="#">
                        <i class="fas fa-trash-alt ico-table" title="vymazat"></i>
                    </a>
                @endif
            @endif
        @endif
    </td>
</tr>

<x-admin.tr-predel/>

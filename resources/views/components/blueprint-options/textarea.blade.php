@props(['data', 'key'])

<tr>
    <td class="nadpis">{{ $data['display'] ?? 'Display: textarea nadpis' }}</td>
    <td>
        @if (isset($data['help']))
            <x-admin.napoveda>
                {{ $data['help'] }}
            </x-admin.napoveda>
        @endif
    </td>
    <td colspan="2">
        <textarea
                name="option_{{ $key }}"
                id="option_{{ $key }}"
                cols="{{ $data['cols'] ?? 48 }}"
                rows="{{ $data['rows'] ?? 5 }}"
                @if (empty($data['editable'])) disabled style="background-color: #ebebeb;color: #999;" @endif
        >{{ old('option_' . $key, $data['value'] ?? '') }}</textarea>
        @if ($errors->has('option_' . $key))
            <div class="validation-error">{{ $errors->first('option_' . $key) }}</div>
        @endif
    </td>
</tr>

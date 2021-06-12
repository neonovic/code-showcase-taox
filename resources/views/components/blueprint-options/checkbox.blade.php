@props(['data', 'key'])

<tr>
    <td class="nadpis">{{ $data['display'] ?? 'Display: checkbox nadpis' }}</td>
    <td>
        @if (isset($data['help']))
            <x-admin.napoveda>
                {{ $data['help'] }}
            </x-admin.napoveda>
        @endif
    </td>
    <td colspan="2">
        <input type="hidden" name="option_{{ $key }}" value="0" />
        <input type="checkbox"
               name="option_{{ $key }}"
               id="option_{{ $key }}"
               value="1"
               {{ $errors->any() ? (old('option_' . $key) ? 'checked' : '') : ($data['value'] ? 'checked' : '') }}
               @if (empty($data['editable'])) disabled @endif
        >
        @if(isset($data['required']))
            <span class="red">*</span>
        @endif
        @if ($errors->has('option_' . $key))
            <div class="validation-error">{{ $errors->first('option_' . $key) }}</div>
        @endif
    </td>
</tr>

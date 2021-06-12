@props(['data', 'key'])

<tr>
    <td class="nadpis">{{ $data['display'] ?? 'Display: input nadpis' }}</td>
    <td>
        @if (isset($data['help']))
            <x-admin.napoveda>
                {{ $data['help'] }}
            </x-admin.napoveda>
        @endif
    </td>
    <td colspan="2">
        <input type="text"
               size="{{ $data['size'] ?? 100 }}"
               name="option_{{ $key }}"
               id="option_{{ $key }}"
               value="{{ old('option_' . $key, $data['value'] ?? '') }}"
               @if (isset($data['maxlength'])) maxlength="{{ $data['maxlength'] }}" @endif
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

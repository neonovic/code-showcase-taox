<tr>
    <td width="150" class="nadpis">Zvolte jazyk:</td>
    <td>&nbsp;</td>
    <td colspan="2">
        <select wire:change="">
            @foreach (config('languages') as $lang => $language)
                <option value="{{ $lang }}" {{ $lang == app()->getLocale() ? 'selected="selected"' : '' }}>{{ $language }}</option>
            @endforeach
        </select>

    </td>
</tr>

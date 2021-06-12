<tr>
    <td colspan="4" class="c-title-sluggale">
        <style>
            .validation-error {
                padding-left: 10px;
                color: red;
            }
            .c-title-sluggale input::placeholder {
                opacity: 0.4;
            }
        </style>
        <div style="display:flex;align-items:center">
            <div style="flex:0 0 248px">Název (v menu)</div>
            <input type="text"
                   wire:model.debounce.300ms="name"
                   name="name"
                   size="100"
                   placeholder="{{ $placeholderName }}"
            > <span class="red">*</span>
            @error('name')
                <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
        <div style="display:flex;align-items:center">
            <div style="flex:0 0 248px">URL (odkaz)</div>
            <input type="text"
                   wire:model.debounce.300ms="slug"
                   name="slug"
                   size="100"
                   placeholder="{{ $placeholderSlug }}"
            > <span class="red">*</span>
            @error('slug')
                <div class="validation-error">{{ $message }}</div>
            @enderror
        </div>
    </td>
</tr>

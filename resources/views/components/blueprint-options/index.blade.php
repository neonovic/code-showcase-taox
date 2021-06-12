<form action="{{ route('admin.option.update', $option->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    <table class="tabulka-detail">

        @foreach ($option->blueprint_data as $key => $settings)
            @foreach (range(0, ($settings['multiple'] ?? 1) - 1) as $i)
                @switch($settings['type'])
                    @case('section')
                    <x-admin.blueprint-options.section :data="$settings" :key="$key"/>
                    @break
                    @case('text')
                    <x-admin.blueprint-options.text :data="$settings" :key="$key"/>
                    @break
                    @case('textarea')
                    <x-admin.blueprint-options.textarea :data="$settings" :key="$key"/>
                    @break
                    @case('wysiwyg')
                    <x-admin.blueprint-options.wysiwyg :data="$settings" :key="$key"/>
                    @break
                    @case('file')
                    <x-admin.blueprint-options.file :data="$settings" :key="$key" :option="$option" :index="$i"/>
                    @break
                    @case('checkbox')
                    <x-admin.blueprint-options.checkbox :data="$settings" :key="$key"/>
                    @break
                @endswitch
            @endforeach
        @endforeach

    </table>
    <div class="napoveda-div2">
        <input type="submit" name="submit_btn" value="Upravit">
    </div>
</form>

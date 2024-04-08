@if(file_exists(public_path($styleFile = 'js/gui.js')))
    <script defer src="{{ asset($styleFile) }}"></script>
@endif

<x-filament::page>
    @include('gui::partials.styles')
    <div id="app">
        <app home="{{ url(config('artisan-gui.home', '/')) }}" endpoint={{ url('admin/artisan/json') }} />
    </div>
</x-filament::page>

@push('scripts')
@include('gui::partials.scripts')
@endpush

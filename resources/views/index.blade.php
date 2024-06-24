<x-filament-panels::page>
    <div id="app">
        <app home="{{ url(config('filament-artisan.home', '/')) }}" endpoint={{ url('admin/artisan/json') }} />
    </div>
</x-filament-panels::page>

@push('scripts')
    @include('filament-artisan::partials.scripts')
@endpush

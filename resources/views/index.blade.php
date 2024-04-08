<x-filament::page>
    @include('filament-artisan::partials.styles')
    <div id="app">
        <app home="{{ url(config('filament-artisan.home', '/')) }}" endpoint={{ url('admin/artisan/json') }} />
    </div>
</x-filament::page>

@push('scripts')
@include('filament-artisan::partials.scripts')
@endpush

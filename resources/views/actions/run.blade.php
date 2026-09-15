@php
    $item = \TomatoPHP\FilamentArtisan\Models\Command::query()->where('name', $action->getArguments()['item'] ?? null)->first();
@endphp

@if($item)
<button
    type="button"
    wire:click="{{ $action->getLivewireClickHandler() }}"
    wire:target="{{ $action->getLivewireTarget() }}"
    x-on:click="{{ $action->getAlpineClickHandler() }}"
    style="height: 100%; width: 100%; display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 0.5rem; padding: 1rem; border: 1px solid rgba(127, 127, 127, 0.25); border-radius: 0.75rem; background: transparent; cursor: pointer; text-align: start;"
>
    @if(! $item->error)
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.125rem; font-weight: 700;">
            <x-filament::icon icon="heroicon-s-command-line" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;" />
            <code>{{ $item->name }}</code>
        </div>

        <div style="opacity: 0.7;">
            {{ $item->description }}
        </div>

        <div style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 0.5rem; margin-top: 0.5rem;">
            @foreach((array) json_decode($item->arguments ?? 'null') as $arg)
                <x-filament::badge color="warning" tooltip="Arguments" icon="heroicon-s-document-text">
                    {{ str($arg->name)->replace('_', ' ')->replace('-', ' ')->title() }}
                </x-filament::badge>
            @endforeach

            @foreach((array) json_decode($item->options ?? 'null') as $op)
                <x-filament::badge color="info" tooltip="Option" icon="heroicon-s-bars-3-center-left">
                    {{ str($op->name)->replace('_', ' ')->replace('-', ' ')->title() }}
                </x-filament::badge>
            @endforeach
        </div>
    @else
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.125rem; font-weight: 700;">
            <x-filament::icon icon="heroicon-s-command-line" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;" />
            <code>{{ $item->name }}</code>
        </div>

        <div style="opacity: 0.7;">{{ $item->error }}</div>
    @endif
</button>
@endif

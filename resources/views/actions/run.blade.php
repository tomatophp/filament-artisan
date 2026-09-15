@php
    $item = \TomatoPHP\FilamentArtisan\Models\Command::query()->where('name', $action->getArguments()['item'] ?? null)->first();
@endphp

@if($item)
<button
    type="button"
    wire:click="{{ $action->getLivewireClickHandler() }}"
    wire:target="{{ $action->getLivewireTarget() }}"
    x-on:click="{{ $action->getAlpineClickHandler() }}"
    class="h-full w-full flex flex-col justify-start items-start bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg p-4 cursor-pointer transition ease-in-out duration-200 text-start"
>
    @if(! $item->error)
        <div class="text-xl mb-2 transition ease-in-out duration-200 font-bold flex justify-start gap-2">
            <div class="flex flex-col justify-center item-center">
                <x-filament::icon icon="heroicon-s-command-line" class="w-4 h-4" />
            </div>
            <div>
                <code>{{ $item->name }}</code>
            </div>
        </div>

        <div class="text-gray-500 dark:text-gray-200">
            {{ $item->description }}
        </div>

        <div class="flex flex-wrap justify-start gap-2 my-4">
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
        <div class="mb-4 flex justify-start gap-2">
            <div class="flex flex-col justify-center item-center">
                <x-filament::icon icon="heroicon-s-command-line" class="w-4 h-4" />
            </div>
            <div>
                {{ $item->name }}
            </div>
        </div>

        <div class="text-gray-500 dark:text-gray-200">{{ $item->error }}</div>
    @endif
</button>
@endif

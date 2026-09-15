{{-- Inline styles: Filament v5 panels only compile the Tailwind classes Filament itself uses. --}}
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(22rem, 1fr)); gap: 1rem; padding: 1rem;">
    @foreach($records as $item)
        {{ ($this->runAction)(['item' => $item->name]) }}
    @endforeach
</div>

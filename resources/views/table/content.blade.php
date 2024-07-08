<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
    @foreach($records as $item)
        {{ ($this->runAction($item))(['item' => $item]) }}
    @endforeach
</div>


@props(['items', 'groupName' => 'default', 'gridClasses' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6'])

@if(isset($items) && method_exists($items, 'count'))
    <div data-pagination-group="{{ $groupName }}">
        <div class="{{ $gridClasses }}">
            @foreach($items as $post)
                @include('components.card_postingan', ['post' => $post])
            @endforeach
        </div>

        @if(method_exists($items, 'hasPages') && $items->hasPages())
            <div class="mt-4">
                {!! $items->render('vendor.pagination.custom_ajax', ['groupName' => $groupName]) !!}
            </div>
        @endif
    </div>
@endif
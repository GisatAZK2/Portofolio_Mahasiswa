{{-- Learning Corner Group with Pagination --}}
<div data-pagination-group="learning_corner" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($learningcorner as $post)
        @include('components.card_postingan', ['post' => $post])
    @endforeach
</div>
{{ $learningcorner->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
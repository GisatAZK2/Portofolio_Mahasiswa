{{-- Project Group with Pagination --}}
<div data-pagination-group="project" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($project as $post)
        @include('components.card_postingan', ['post' => $post])
    @endforeach
</div>
{{ $project->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
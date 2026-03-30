{{-- Project User Group with Pagination --}}
<div data-pagination-group="project_user" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($projectuser as $post)
        @include('components.card_postingan', ['post' => $post])
    @endforeach
</div>
{{ $projectuser->render('vendor.pagination.custom_ajax', ['groupName' => 'project_user']) }}
{{-- Project User Group with Pagination (reusable) --}}
@include('components.pagination_group', [
    'items' => $projectuser,
    'groupName' => 'project_user',
    'gridClasses' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6'
])
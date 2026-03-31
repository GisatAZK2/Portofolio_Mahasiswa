{{-- Sertifikat Group with Pagination (reusable) --}}
@include('components.pagination_group', [
    'items' => $sertifikat,
    'groupName' => 'sertifikat',
    'gridClasses' => 'grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-3 sm:gap-6'
])
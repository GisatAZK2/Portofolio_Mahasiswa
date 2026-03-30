{{-- Sertifikat Group with Pagination --}}
<div data-pagination-group="sertifikat" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
    @foreach($sertifikat as $post)
        @include('components.card_postingan', ['post' => $post])
    @endforeach
</div>
{{ $sertifikat->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
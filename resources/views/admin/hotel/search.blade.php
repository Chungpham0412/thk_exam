<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
@endsection

<!-- main containts -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">検索画面</h2>
        <hr>
        <div class="search-hotel-name">
            <form id="search-form" action="{{ route('adminHotelSearchResult') }}" method="post">
                @csrf
                <input type="text" name="hotel_name" id="hotel_name" value="" class="form-control" placeholder="ホテル名">
                <button type="submit" class="btn">検索</button>
            </form>
        </div>
        <hr>
    </div>
    @yield('search_results')
@endsection
@section('page_js')
<script>
    document.getElementById('search-form').addEventListener('submit', function(event) {
        const hotelNameInput = document.getElementById('hotel_name');

        if (hotelNameInput.value.trim().length === 0) {
            event.preventDefault();
            alert('ホテル名を入力してください。');
        }
    });
</script>
@endsection

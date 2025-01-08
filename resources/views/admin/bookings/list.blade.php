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
        <div class="search-booking-name">
            <form id="search-form" action="{{ route('admin.bookings.search') }}" method="post">
                @csrf
                <div class="list_search">
                    <div class="form-group">
                        <label for="customer_name">顧客名</label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ !empty($request['customer_name']) ? $request['customer_name'] : '' }}" class="form-control" placeholder="顧客名">
                    </div>
                    <div class="form-group">
                        <label for="customer_contact">お客様連絡先</label>
                        <input type="text" name="customer_contact" id="customer_contact" value="{{ !empty($request['customer_contact']) ? $request['customer_contact'] : '' }}" class="form-control" placeholder="お客様連絡先">
                    </div>
                    <div class="form-group">
                        <label for="chekin_time">チェックイン日</label>
                        <input type="date" name="chekin_time" id="chekin_time" value="{{ !empty($request['chekin_time']) ? $request['chekin_time'] : '' }}" class="form-control" placeholder="チェックイン日">
                    </div>
                    <div class="form-group">
                        <label for="checkout_time">チェックアウト日</label>
                        <input type="date" name="checkout_time" id="checkout_time" value="{{ !empty($request['checkout_time']) ? $request['checkout_time'] : '' }}" class="form-control" placeholder="チェックアウト日">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">検索</button>

                    </div>
                </div>
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

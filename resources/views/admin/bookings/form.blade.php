<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/hotel.scss')
@endsection

<!-- main containts -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">検索画面</h2>
        <hr>
        <div class="booking-create-container">
            @session('success')
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endsession
            @session('error')
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endsession
            <form id="multi-step-form" action="{{ $route }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($method == 'PUT')
                    @method('PUT')
                @endif
                <div id="step-1" class="active">
                    <h3>情報</h3>
                    <!-- Hotel -->
                    <div class="form-group">
                        <label for="hotel">ホテル名 <span class="required">*</span></label>
                        <select name="hotel_id" id="hotel" class="form-control" required>
                            <option value="">-- ホテルを選択 --</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->hotel_id }}" {{ (!empty($booking) && $hotel->hotel_id == $booking->hotel_id) || old('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>
                                    {{ $hotel->hotel_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- 顧客名 -->
                    <div class="form-group">
                        <label for="customer_name">顧客名 <span class="required">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ $booking->customer_name ?? old('customer_name') }}" placeholder="顧客名を入力してください" required>
                        @error('customer_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- お客様連絡先 -->
                    <div class="form-group">
                        <label for="customer_contact">お客様連絡先 <span class="required">*</span></label>
                        <input type="text" name="customer_contact" id="customer_contact" value="{{ $booking->customer_contact ?? old('customer_contact') }}" placeholder="顧客連絡先を入力してください" required>
                        @error('customer_contact')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- チェックイン日 -->
                    <div class="form-group">
                        <label for="chekin_time">チェックイン日 <span class="required">*</span></label>
                        <input type="date" name="chekin_time" id="chekin_time" value="{{ $booking->chekin_time ?? old('chekin_time') }}" required>
                        @error('chekin_time')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- チェックアウト日 -->
                    <div class="form-group">
                        <label for="checkout_time">チェックアウト日 <span class="required">*</span></label>
                        <input type="date" name="checkout_time" id="checkout_time" value="{{ $booking->checkout_time ?? old('checkout_time') }}" required>
                        @error('checkout_time')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <button type="button" class="btn btn-primary" onclick="nextStep()">Tiếp tục</button>
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Quay lại</button> --}}
                    <div class="form-actions">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">キャンセル</a>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">確認する</button>
                    </div>
                </div>
                <!-- Bước 2: Xác nhận -->
                <div id="step-2" style="display: none;">
                    <h3>Preview</h3>
                    <p>ホテル名: <span id="confirm-hotel-name"></span></p>
                    <p>顧客名: <span id="confirm-customer-name"></span></p>
                    <p>お客様連絡先: <span id="confirm-customer-contact"></span></p>
                    <p>チェックイン日: <span id="confirm-checkin-date"></span></p>
                    <p>チェックアウト日: <span id="confirm-checkout-date"></span></p>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">戻る</button>
                        <button type="submit" class="btn btn-primary" onclick="nextStep()">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @yield('search_results')
@endsection
@section('page_js')
<script>
let currentStep = 1;
function nextStep() {
    // Validate bước 1
    if (currentStep === 1) {
        const hotelSelect = document.getElementById('hotel');
        const customerName = document.getElementById('customer_name').value;
        const customerContact = document.getElementById('customer_contact').value;
        const checkinDate = document.getElementById('chekin_time').value;
        const checkoutDate = document.getElementById('checkout_time').value;
        if (!hotelSelect.value || !customerName || !customerContact || !checkinDate || !checkoutDate) {
            alert("このステップでは完全な情報を入力してください。");
            return; // Dừng nếu không hợp lệ
        }

        // Hiển thị thông tin xác nhận
        document.getElementById('confirm-hotel-name').innerText = hotelSelect.options[hotelSelect.selectedIndex].text;
        document.getElementById('confirm-customer-name').innerText = customerName;
        document.getElementById('confirm-customer-contact').innerText = customerContact;
        document.getElementById('confirm-checkin-date').innerText = checkinDate;
        document.getElementById('confirm-checkout-date').innerText = checkoutDate;
    }

    // Chuyển sang bước tiếp theo
    document.getElementById(`step-${currentStep}`).style.display = 'none';
    currentStep++;
    document.getElementById(`step-${currentStep}`).style.display = 'block';
}

function prevStep() {
    // Quay lại bước trước
    document.getElementById(`step-${currentStep}`).style.display = 'none';
    currentStep--;
    document.getElementById(`step-${currentStep}`).style.display = 'block';
}
</script>
@endsection

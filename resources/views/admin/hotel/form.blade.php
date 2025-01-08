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
        <div class="hotel-create-container">
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
                <div id="step-1" class="active">
                    <h3>情報</h3>
                    <!-- ホテル名 -->
                    <div class="form-group">
                        <label for="hotel_name">ホテル名 <span class="required">*</span></label>
                        <input type="text" name="hotel_name" id="hotel_name" value="{{ $hotel->hotel_name ?? old('hotel_name') }}" placeholder="Enter hotel name" required>
                        @error('hotel_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Prefecture -->
                    <div class="form-group">
                        <label for="prefecture">Prefecture <span class="required">*</span></label>
                        <select name="prefecture_id" id="prefecture" required>
                            <option value="">-- Select Prefecture --</option>
                            @foreach($prefectures as $prefecture)
                                <option value="{{ $prefecture->prefecture_id }}" {{ (!empty($hotel) && $hotel->prefecture_id == $prefecture->prefecture_id ) || old('prefecture_id') == $prefecture->prefecture_id ? 'selected' : '' }}>
                                    {{ $prefecture->prefecture_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('prefecture_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="hidden" name="file_path" id="image" accept="image/*" onchange="previewImage(event)" value="{{$hotel->file_path ?? ''}}">
                        <input type="file" name="image_hotel" id="image_hotel" accept="image/*" onchange="previewImage(event)" value="{{$hotel->file_path ?? ''}}">
                        @error('file_path')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        <!-- Image Preview -->
                        <div class="image-preview">
                            @if(!empty($hotel) && $hotel->file_path)
                                <img id="image-preview" src="/assets/img/{{ $hotel->file_path }}" alt="Image">
                                <button type="button" id="delete-image-btn" class="btn btn-danger" onclick="deleteImage()">Xóa ảnh</button>
                            @else
                                <img id="image-preview" src="#" alt="Image" style="display: none;">
                                <button type="button" id="delete-image-btn" class="btn btn-danger" style="display: none;" onclick="deleteImage()">Xóa ảnh</button>
                            @endif
                        </div>
                    </div>
                    {{-- <button type="button" class="btn btn-primary" onclick="nextStep()">Tiếp tục</button>
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Quay lại</button> --}}
                    <div class="form-actions">
                        <a href="http://localhost/admin/hotel/search" class="btn btn-secondary">キャンセル</a>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">確認する</button>
                    </div>
                </div>
                <!-- Bước 2: Xác nhận -->
                <div id="step-2" style="display: none;">
                    <h3>Preview</h3>
                    <p>ホテル名: <span id="confirm-hotel-name"></span></p>
                    <p>Prefecture: <span id="confirm-prefecture"></span></p>
                    <p>
                        <img id="confirm-image-preview" src="" alt="Hotel Preview" style="max-width: 200px; display: none;">
                    </p>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">戻る</button>
                        <button type="submit" class="btn btn-primary" onclick="nextStep()">Submit</button>
                    </div>
                </div>
                <!-- Submit Button -->
                {{-- <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('adminHotelSearchPage') }}" class="btn btn-secondary">キャンセル</a>
                </div> --}}
            </form>
        </div>
    </div>
    @yield('search_results')
@endsection
@section('page_js')
<script>
 function previewImage(event) {
    const imagePreview = document.getElementById('image-preview');
    const deleteButton = document.getElementById('delete-image-btn');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            deleteButton.style.display = 'inline-block';
        };

        reader.readAsDataURL(file);
    }
}

function deleteImage() {
    const imagePreview = document.getElementById('image-preview');
    const deleteButton = document.getElementById('delete-image-btn');
    const imageInput = document.getElementById('image');

    // Reset input file
    imageInput.value = '';
    // Hide preview image
    imagePreview.style.display = 'none';
    // Hide delete button
    deleteButton.style.display = 'none';
}
let currentStep = 1;

function nextStep() {
    // Validate bước 1
    if (currentStep === 1) {
        const hotelName = document.getElementById('hotel_name').value.trim();
        const prefecture = document.getElementById('prefecture').value;
        const imageInput = document.getElementById('image');
        const imageHotel = document.getElementById('image_hotel');
console.log('imageHotel', imageHotel);
        if (!hotelName || !prefecture) {
            alert("このステップでは完全な情報を入力してください。");
            return; // Dừng nếu không hợp lệ
        }

        // Hiển thị thông tin xác nhận
        document.getElementById('confirm-hotel-name').innerText = hotelName;
        const prefectureSelect = document.getElementById('prefecture');
        const selectedPrefecture = prefectureSelect.options[prefectureSelect.selectedIndex].text;
        document.getElementById('confirm-prefecture').innerText = selectedPrefecture;

         // Hiển thị hình ảnh preview
         const imagePreview = document.getElementById('confirm-image-preview');
         if (imageHotel.files && imageHotel.files[0]) {
            console.log('first if');
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(imageHotel.files[0]);
        }
        if (imageInput.value) {
            // Check if the value is a valid path or URL
            imagePreview.src = '/assets/img/' + imageInput.value;
            imagePreview.style.display = 'block';
        }
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

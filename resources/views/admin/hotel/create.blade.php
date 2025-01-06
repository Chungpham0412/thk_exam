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
            <form action="{{ route('adminHotelCreateProcess') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Hotel Name -->
                <div class="form-group">
                    <label for="hotel_name">Hotel Name <span class="required">*</span></label>
                    <input type="text" name="hotel_name" id="hotel_name" value="{{ old('hotel_name') }}" placeholder="Enter hotel name">
                    @error('hotel_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Prefecture -->
                <div class="form-group">
                    <label for="prefecture">Prefecture <span class="required">*</span></label>
                    <select name="prefecture_id" id="prefecture" >
                        <option value="">-- Select Prefecture --</option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture_id }}" {{ old('prefecture_id') == $prefecture->prefecture_id ? 'selected' : '' }}>
                                {{ $prefecture->prefecture_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('prefecture_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hotel Image -->
                <div class="form-group">
                    <label for="image">Hotel Image</label>
                    <input type="file" name="file_path" id="image" accept="image/*">
                    @error('file_path')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Hotel</button>
                    <a href="{{ route('adminHotelSearchPage') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    @yield('search_results')
@endsection

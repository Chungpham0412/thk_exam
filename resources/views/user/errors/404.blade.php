<!-- base view -->
@extends('common.user.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/user/errors/404.scss')
@endsection

<!-- main contents -->
@section('main_contents')
<div class="error-container">
    <h1 class="error-title">404</h1>
    <p class="error-message">Oops! The page you are looking for doesn’t exist.</p>
    <a href="/" class="error-button">戻る to Home</a>
</div>
@endsection

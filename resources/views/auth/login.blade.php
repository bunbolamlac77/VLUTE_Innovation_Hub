{{-- resources/views/auth/login.blade.php --}}
@php($activeTab = 'login')
@include('auth.auth', ['activeTab' => $activeTab])
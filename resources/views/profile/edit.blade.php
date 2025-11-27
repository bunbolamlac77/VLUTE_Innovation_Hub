@extends('layouts.main')

@section('title', 'Hồ sơ cá nhân')

@section('content')
    <div class="container" style="padding: 24px 0;">
        <h1 class="text-2xl font-semibold mb-4">Hồ sơ cá nhân</h1>

        <div class="grid" style="grid-template-columns: 1fr; gap: 16px;">
            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection

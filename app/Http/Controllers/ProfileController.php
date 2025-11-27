<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $faculties = \App\Models\Faculty::orderBy('name')->get(['id','name']);

        return view('profile.edit', [
            'user' => $user,
            'faculties' => $faculties,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        DB::transaction(function () use ($request, $user, $data) {
            // Cập nhật USER: name, email
            $user->name = $data['name'] ?? $user->name;
            if (($data['email'] ?? $user->email) !== $user->email) {
                $user->email = $data['email'];
                $user->email_verified_at = null; // buộc verify lại khi đổi email
            }

            // Upload avatar nếu có
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar_url = 'storage/' . $path;
            }

            $user->save();

            // Cập nhật PROFILE: phone, address, faculty_id, company_name, position
            $profile = $user->profile ?: new \App\Models\Profile(['user_id' => $user->id]);
            $profile->phone = $data['phone'] ?? $profile->phone;
            if (Schema::hasColumn('profiles', 'address') && array_key_exists('address', $data)) {
                $profile->address = $data['address'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'faculty_id') && array_key_exists('faculty_id', $data)) {
                $profile->faculty_id = $data['faculty_id'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'company_name') && array_key_exists('company_name', $data)) {
                $profile->company_name = $data['company_name'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'position') && array_key_exists('position', $data)) {
                $profile->position = $data['position'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'company_address') && array_key_exists('company_address', $data)) {
                $profile->company_address = $data['company_address'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'department') && array_key_exists('department', $data)) {
                $profile->department = $data['department'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'class_name') && array_key_exists('class_name', $data)) {
                $profile->class_name = $data['class_name'] ?: null;
            }
            if (Schema::hasColumn('profiles', 'school_year') && array_key_exists('school_year', $data)) {
                $profile->school_year = $data['school_year'] ?: null;
            }
            $profile->save();
        });

        return Redirect::route('profile.edit')->with('status', 'Cập nhật hồ sơ thành công.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

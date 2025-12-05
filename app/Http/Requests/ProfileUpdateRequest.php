<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        // Dùng cơ chế kiểm tra vai trò thống nhất với view (hasRole) thay vì chỉ đọc cột role
        $isStaff = $user->hasRole('staff') || $user->hasRole('center') || $user->hasRole('board');
        $isEnterprise = $user->hasRole('enterprise');
        $isStudent = $user->hasRole('student');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ];

        if ($isStudent) {
            // Sinh viên: ảnh, tên, sđt, email, địa chỉ, lớp, khoa
            $rules['class_name'] = ['nullable', 'string', 'max:100'];
            $rules['school_year'] = ['nullable', 'string', 'max:50'];
            $rules['faculty_id'] = ['nullable', 'integer', 'exists:faculties,id'];
            // Cấm các trường khác
            $rules['department'] = ['prohibited'];
            $rules['company_name'] = ['prohibited'];
            $rules['position'] = ['prohibited'];
            $rules['company_address'] = ['prohibited'];
        } elseif ($isStaff) {
            // GV/Trung tâm/BGH: ảnh, tên, sđt, email, địa chỉ, khoa, phòng ban
            $rules['faculty_id'] = ['nullable', 'integer', 'exists:faculties,id'];
            $rules['department'] = ['nullable', 'string', 'max:255'];
            // Cấm trường của SV/DN
            $rules['class_name'] = ['prohibited'];
            $rules['school_year'] = ['prohibited'];
            $rules['company_name'] = ['prohibited'];
            $rules['position'] = ['prohibited'];
            $rules['company_address'] = ['prohibited'];
        } elseif ($isEnterprise) {
            // Doanh nghiệp: ảnh, tên, sđt, email, địa chỉ, công ty, vị trí, địa chỉ công ty
            $rules['company_name'] = ['nullable', 'string', 'max:255'];
            $rules['position'] = ['nullable', 'string', 'max:255'];
            $rules['company_address'] = ['nullable', 'string', 'max:255'];
            // Cấm trường của SV/GV
            $rules['faculty_id'] = ['prohibited'];
            $rules['department'] = ['prohibited'];
            $rules['class_name'] = ['prohibited'];
            $rules['school_year'] = ['prohibited'];
        }

        return $rules;
    }
}

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Cập nhật thông tin cá nhân
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Cập nhật họ tên, email, số điện thoại, địa chỉ và ảnh đại diện của bạn. Các trường nâng cao chỉ hiện cho
            Giảng viên/Trung tâm/BGH hoặc Doanh nghiệp.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Họ và tên --}}
        <div>
            <x-input-label for="name" value="Họ và tên" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        Email của bạn chưa được xác thực.
                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Gửi lại email xác thực
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Link xác thực mới đã được gửi tới email của bạn.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Số điện thoại --}}
        <div>
            <x-input-label for="phone" value="Số điện thoại" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                :value="old('phone', optional($user->profile)->phone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Địa chỉ --}}
        <div>
            <x-input-label for="address" value="Địa chỉ" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                :value="old('address', optional($user->profile)->address)" autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Ảnh đại diện --}}
        <div>
            <x-input-label for="avatar" value="Ảnh đại diện" />
            <div class="flex items-center gap-4 mt-1">
                <img id="avatarPreview" src="{{ $user->avatar_url ? asset($user->avatar_url) : asset('images/avatar-default.jpg') }}" alt="Avatar"
                     class="w-16 h-16 rounded-full object-cover border" />
                <input id="avatar" name="avatar" type="file" accept="image/*" class="block" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const input = document.getElementById('avatar');
                    const img = document.getElementById('avatarPreview');
                    if (input && img) {
                        input.addEventListener('change', function(e) {
                            const file = e.target.files && e.target.files[0];
                            if (!file) return;
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                img.src = ev.target.result;
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });
            </script>
            @endpush
        </div>

        {{-- Thông tin mở rộng theo vai trò --}}
        @php($isStaff = $user->hasRole('staff') || $user->hasRole('center') || $user->hasRole('board'))
        @php($isEnterprise = $user->hasRole('enterprise'))
        @php($isStudent = $user->hasRole('student'))

        {{-- Sinh viên: Lớp, Khoa --}}
        @if ($isStudent)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="class_name" value="Lớp" />
                    <x-text-input id="class_name" name="class_name" type="text" class="mt-1 block w-full"
                        :value="old('class_name', optional($user->profile)->class_name)" />
                    <x-input-error class="mt-2" :messages="$errors->get('class_name')" />
                </div>
                <div>
                    <x-input-label for="faculty_id" value="Khoa" />
                    <select id="faculty_id" name="faculty_id" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="">-- Chọn khoa --</option>
                        @foreach(($faculties ?? []) as $f)
                            <option value="{{ $f->id }}" @selected(old('faculty_id', optional($user->profile)->faculty_id) == $f->id)>
                                {{ $f->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('faculty_id')" />
                </div>
            </div>
        @endif

        {{-- GV/TT/BGH: Khoa, Phòng ban --}}
        @if ($isStaff)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="faculty_id" value="Khoa" />
                    <select id="faculty_id" name="faculty_id" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="">-- Chọn khoa --</option>
                        @foreach(($faculties ?? []) as $f)
                            <option value="{{ $f->id }}" @selected(old('faculty_id', optional($user->profile)->faculty_id) == $f->id)>
                                {{ $f->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('faculty_id')" />
                </div>
                <div>
                    <x-input-label for="department" value="Phòng ban" />
                    <x-text-input id="department" name="department" type="text" class="mt-1 block w-full"
                        :value="old('department', optional($user->profile)->department)" />
                    <x-input-error class="mt-2" :messages="$errors->get('department')" />
                </div>
            </div>
        @endif

        {{-- Doanh nghiệp: Công ty, Vị trí, Địa chỉ Công ty --}}
        @if ($isEnterprise)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="company_name" value="Công ty / Đơn vị" />
                    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full"
                        :value="old('company_name', optional($user->profile)->company_name)" />
                    <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                </div>
                <div>
                    <x-input-label for="position" value="Vị trí / Chức vụ" />
                    <x-text-input id="position" name="position" type="text" class="mt-1 block w-full"
                        :value="old('position', optional($user->profile)->position)" />
                    <x-input-error class="mt-2" :messages="$errors->get('position')" />
                </div>
                <div>
                    <x-input-label for="company_address" value="Địa chỉ công ty" />
                    <x-text-input id="company_address" name="company_address" type="text" class="mt-1 block w-full"
                        :value="old('company_address', optional($user->profile)->company_address)" />
                    <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>Lưu</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">Đã lưu.</p>
            @endif
        </div>
    </form>
</section>

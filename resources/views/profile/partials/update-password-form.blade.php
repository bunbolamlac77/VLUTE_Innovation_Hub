<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Đổi mật khẩu</h2>
        <p class="mt-1 text-sm text-gray-600">Nên dùng mật khẩu mạnh (tối thiểu 8 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt).</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Mật khẩu hiện tại" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Mật khẩu mới" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <div class="pw-bar"><span id="pwStrength"></span></div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Xác nhận mật khẩu" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Lưu mật khẩu</x-primary-button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Đã lưu.</p>
            @endif
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const pw = document.getElementById('update_password_password');
            const bar = document.getElementById('pwStrength');
            if (pw && bar) {
                pw.addEventListener('input', function(){
                    const v = pw.value;
                    let s = 0;
                    if (v.length >= 8) s+=25;
                    if (/[A-Z]/.test(v)) s+=25;
                    if (/[a-z]/.test(v)) s+=25;
                    if (/[0-9\W]/.test(v)) s+=25;
                    bar.style.width = s + '%';
                });
            }
        });
    </script>
    @endpush
</section>

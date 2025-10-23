<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (auth()->user()?->role === 'admin')
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 py-6">
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-4">
                <strong>Admin:</strong>
                <a href="{{ route('admin.approvals.index') }}" class="ml-2 font-semibold text-blue-600 hover:underline">
                    Quản lý phê duyệt tài khoản
                </a>
            </div>
        </div>
    @endif

    {{-- Nội dung dashboard khác của bạn --}}
    <p>Vai trò: <strong>{{ auth()->user()->role_label }}</strong></p>
</x-app-layout>
{{-- resources/views/admin/approvals/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Phê duyệt tài khoản
        </h2>
        <p class="text-sm text-gray-500 mt-1">Email @st.vlute.edu.vn → Sinh viên (auto). Email @vlute.edu.vn → chọn Giảng viên / Trung tâm ĐMST / Ban giám hiệu. Domain khác → Doanh nghiệp.</p>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-5 rounded-lg bg-emerald-50 px-4 py-3 text-emerald-700 font-medium ring-1 ring-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @if ($pending->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white/60 p-10 text-center shadow-sm">
                    <div class="text-gray-600">Hiện không có tài khoản chờ phê duyệt.</div>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left text-gray-600">
                                <tr>
                                    <th class="px-5 py-3 font-semibold w-[30%]">Email</th>
                                    <th class="px-5 py-3 font-semibold w-[22%]">Họ tên</th>
                                    <th class="px-5 py-3 font-semibold w-[22%]">Role gợi ý</th>
                                    <th class="px-5 py-3 font-semibold w-[16%]">Chọn role khi duyệt</th>
                                    <th class="px-5 py-3 font-semibold text-center w-[10%]">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pending as $row)
                                    @php
                                        /** @var \App\Models\User $u */
                                        $u = $row['model'];
                                        $suggested = $row['suggested']; // mảng slug: ví dụ ['staff','center','board']
                                        $current   = $u->role ?? ($suggested[0] ?? null);
                                    @endphp

                                    <tr class="odd:bg-white even:bg-gray-50/40">
                                        <td class="px-5 py-4">
                                            <div class="font-medium text-gray-900">{{ $u->email }}</div>
                                            <div class="mt-0.5 text-xs text-gray-500">Tạo: {{ $u->created_at?->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-gray-800">{{ $u->name ?? '—' }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($suggested as $s)
                                                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-200">
                                                        {{ \App\Models\User::roleLabel($s) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <form action="{{ route('admin.approvals.approve', $u) }}" method="POST" class="flex items-center gap-3">
                                                @csrf
                                                <select name="role" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    @foreach ($suggested as $s)
        <option value="{{ $s }}" @selected(($u->role ?? ($suggested[0] ?? null)) === $s)>
            {{ \App\Models\User::roleLabel($s) }}
        </option>
    @endforeach
</select>

                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3.5 py-2.5 text-white text-sm font-semibold shadow-sm hover:bg-emerald-700">
                                                    Duyệt
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-5 py-4">
                                            <form action="{{ route('admin.approvals.reject', $u) }}" method="POST" class="flex items-center justify-center">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-3.5 py-2.5 text-white text-sm font-semibold shadow-sm hover:bg-rose-700">
                                                    Từ chối
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Về Dashboard</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
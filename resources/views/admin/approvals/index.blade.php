<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phê duyệt tài khoản') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-emerald-700 font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            @if ($pending->isEmpty())
                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <p>Hiện không có tài khoản chờ duyệt.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Họ tên</th>
                                <th class="px-4 py-3">Role gợi ý</th>
                                <th class="px-4 py-3">Chọn role khi duyệt</th>
                                <th class="px-4 py-3 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($pending as $u)
                                @php
                                    $email = strtolower($u->email);
                                    $domain = \Illuminate\Support\Str::of($email)->after('@')->toString();
                                    $suggested = $domain === 'st.vlute.edu.vn' ? 'student' : ($domain === 'vlute.edu.vn' ? 'staff' : 'enterprise');
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">{{ $u->email }}</td>
                                    <td class="px-4 py-3">{{ $u->name }}</td>
                                    <td class="px-4 py-3">
                                        <code class="rounded bg-gray-100 px-2 py-1">{{ $suggested }}</code>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('admin.approvals.approve', $u) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <select name="role" class="rounded-lg border-gray-300">
                                                @foreach (['student','staff','enterprise','admin'] as $r)
                                                    <option value="{{ $r }}" @selected(($u->role ?? $suggested) === $r)>{{ ucfirst($r) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700">
                                                Duyệt
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('admin.approvals.reject', $u) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-rose-600 px-3 py-2 text-white hover:bg-rose-700">
                                                Từ chối
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Về Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
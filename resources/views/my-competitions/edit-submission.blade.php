<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chỉnh sửa bài nộp: {{ $competition->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 text-sm">
                <a href="{{ route('my-competitions.index') }}" class="text-indigo-600 hover:underline">← Quay lại danh sách</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('competitions.show', $competition->slug) }}" class="text-indigo-600 hover:underline">Chi tiết cuộc thi</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('competitions.submit.create', $registration->id) }}" class="text-indigo-600 hover:underline">Trang nộp bài</a>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                        <div class="p-6 text-gray-900">
                            @if ($errors->any())
                                <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3">
                                    <div class="font-semibold mb-1">Vui lòng kiểm tra lại:</div>
                                    <ul class="list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('competitions.submit.update', $submission) }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tiêu đề bài nộp <span class="text-rose-600">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $submission->title) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('title')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tóm tắt (tuỳ chọn)</label>
                                    <textarea name="abstract" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('abstract', $submission->abstract) }}</textarea>
                                    @error('abstract')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">File đã đính kèm</label>
                                    @if ($submission->attachments->count())
                                        <ul class="mt-2 divide-y divide-gray-200 rounded-lg border border-gray-200">
                                            @foreach ($submission->attachments as $att)
                                                <li class="flex items-center justify-between gap-3 px-3 py-2 text-sm">
                                                    <a href="{{ route('attachments.download', $att->id) }}" class="text-indigo-600 hover:underline truncate">{{ $att->filename }}</a>
                                                    <label class="inline-flex items-center gap-2 text-rose-600">
                                                        <input type="checkbox" name="remove_attachments[]" value="{{ $att->id }}" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500" />
                                                        Xoá file này
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500">Chưa có file đính kèm.</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Thêm file mới (có thể chọn nhiều)</label>
                                    <input type="file" name="files[]" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" multiple class="mt-1 block w-full" />
                                    <p class="mt-1 text-xs text-gray-500">Tối đa 20MB mỗi file. Hỗ trợ: pdf, doc, docx, zip, ppt, pptx</p>
                                    @error('files')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                    @error('files.*')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('competitions.submit.create', $registration->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Hủy</a>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Cập nhật</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('competitions.submit.destroy', $submission) }}" class="mt-4" onsubmit="return confirm('Bạn chắc chắn xoá bài nộp này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 rounded-md bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100">Xoá bài nộp</button>
                            </form>
                        </div>
                    </div>
                </div>
                <aside>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                        <div class="p-6">
                            @php
                                $end = $competition->end_date;
                                $isOpen = $competition->status === 'open' && (!$end || $end->isFuture());
                            @endphp
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold">Thông tin cuộc thi</h3>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isOpen ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">{{ $competition->status }}</span>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2">
                                @if ($competition->start_date)
                                    <li>Bắt đầu: <strong>{{ $competition->start_date->format('d/m/Y H:i') }}</strong></li>
                                @endif
                                @if ($competition->end_date)
                                    <li>Hạn chót: <strong>{{ $competition->end_date->format('d/m/Y H:i') }}</strong></li>
                                    <li class="text-xs text-gray-400">{{ $isOpen ? 'Còn ' . $competition->end_date->diffForHumans(null, true) : 'Đã kết thúc ' . $competition->end_date->diffForHumans() }}</li>
                                @endif
                                <li>Nhóm: <strong>{{ $registration->team_name ?? '(Cá nhân)' }}</strong></li>
                            </ul>

                            <div class="mt-6">
                                <h4 class="font-semibold mb-2">Thành viên & Mentor</h4>
                                <div class="rounded-lg border border-gray-200 p-3 text-sm text-gray-700">
                                    <div>- Thành viên: <strong>{{ auth()->user()->name }}</strong> (Bạn)</div>
                                    <div>- Mentor: <span class="text-gray-500">Chưa cập nhật</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>


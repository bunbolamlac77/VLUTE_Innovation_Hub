<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nộp bài dự thi: {{ $registration->competition->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('competitions.submit.store', $registration->id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tiêu đề bài nộp</label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tóm tắt (tuỳ chọn)</label>
                            <textarea name="abstract" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('abstract') }}</textarea>
                            @error('abstract')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tập tin</label>
                            <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" required class="mt-1 block w-full" />
                            <p class="mt-1 text-xs text-gray-500">Dung lượng tối đa 20MB. Định dạng: pdf, doc, docx, zip, ppt, pptx</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('my-competitions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md mr-2">Hủy</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Nộp bài</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


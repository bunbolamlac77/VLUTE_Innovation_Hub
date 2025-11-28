<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dự án đang hướng dẫn (Mentoring)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($mentoredIdeas->isEmpty())
                        <p class="text-center text-gray-500">Bạn chưa hướng dẫn dự án nào.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên dự án</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sinh viên thực hiện</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khoa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Danh mục</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mentoredIdeas as $idea)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $idea->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $idea->owner->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $idea->faculty->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $idea->category->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $idea->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('my-ideas.show', $idea->id) }}" class="text-indigo-600 hover:text-indigo-900">Vào cố vấn</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $mentoredIdeas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


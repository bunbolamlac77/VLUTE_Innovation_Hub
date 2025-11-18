<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bản tin Khoa học</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Bản tin Khoa học Mới nhất</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($news as $item)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 mb-2">{{ $item->category }} - {{ $item->published_date->format('d/m/Y') }}</p>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $item->title }}</h2>
                        <p class="text-gray-600 mb-4">{{ $item->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Tác giả: {{ $item->author }}</span>
                            <a href="#" class="text-blue-600 hover:underline">Đọc thêm &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>

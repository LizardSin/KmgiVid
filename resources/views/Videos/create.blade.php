<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Video app</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
<div class="max-w-lg mx-auto py-8">
    <form action="/" method="post" class="flex items-center justify-between border border-gray-300 p-4 rounded" enctype="multipart/form-data">
        @csrf
        <input type="file" name="video" id="video">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded " >Загрузить видео</button>
    </form>
</div>
</body>
</html>

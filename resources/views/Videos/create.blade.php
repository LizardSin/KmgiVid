<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name = "viewport" content="width = device-width, initial-scale = 1.0">
    <meta http-equiv="X-UA-Compayible" content = "ie=edge">
    <title> Upload @yield('title') </title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">

</head>
<body>
<div class="max-w-lg mx-auto py-8">
    <form class="form" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="video" id="video">
        <button class = "btn" type = "submit">Submit</button>
    </form>
</div>

@yield('results')
</body>
</html>

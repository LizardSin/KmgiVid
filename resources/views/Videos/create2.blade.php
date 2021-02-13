<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name = "viewport" content="width = device-width, initial-scale = 1.0">
    <meta http-equiv="X-UA-Compayible" content = "ie=edge">
    <title> Upload @yield('title') </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body style="background: #da513d">
<div class = "d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">

<div class=" text-center py-3 px-3 float-left mw-25" >

    <form class="form" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class = "custom-file my-1">
        <input type="file" class="custom-file-input" name="video" id = "video">
        <label class="custom-file-label" for="video">Choose file</label>
        </div>

        <button class = "btn btn-outline-light btn-block text-center" type = "submit">Submit</button>
    </form>
    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
</div>
<div class="container float-right mw-50 text-center py-3 px-3">

    @yield('results')

</div>
</div>
<nav class="navbar fixed-bottom navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('Trunc')}}">Clear Database</a>
</nav>
</body>
</html>

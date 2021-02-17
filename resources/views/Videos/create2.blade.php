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
<body style="background: #666666">
<div class = "d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">

<div class=" text-center py-3 px-3 float-left mw-25" >


    <form class="form" action="" method="POST" enctype="multipart/form-data" id ="video-upload">
        @csrf

        <div class = "custom-file border border-dark border rounded mb-2">
        <input type="file" class="custom-file-input " name="video" id = "video">
        <label class="custom-file-label text-left" for="video">Choose file</label>
        </div>

        <button class = "btn btn-outline-light btn-block text-center" type = "submit">Submit</button>

        <br>
        <div class  ="">
        <h5 class = "text-white my-2 text-left"> Select method</h5>
        <div class="custom-control custom-radio my-2 text-left text-white">
            <input type="radio" id="customRadio1" value = "content" name="radio" class="custom-control-input">
            <label class="custom-control-label" for="customRadio1">Content Moderation</label>
        </div>
        <div class="custom-control custom-radio my-1 text-left text-white">
            <input type="radio" id="customRadio2" value="label" name="radio" class="custom-control-input">
            <label class="custom-control-label" for="customRadio2">Label Detection</label>
        </div>
        </div>
    </form>
    <script>
        $('.file-upload').file_upload();
    </script>
    <br>
    @yield('video-link')

    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
</div>
<div class="container mw-50 text-center py-3 px-3 mx-auto">

    @yield('results')

</div>
</div>
<nav class="navbar fixed-bottom navbar-dark bg-dark" style="height: 45px">
    <a class="navbar-brand" href="{{route('Trunc')}}">
        <h5>
            Clear Database
        </h5>
    </a>
</nav>
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body style="background: #da513d">

    <header class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <a class ="my-0 mr-md-2 font-weight-normal" href="{{$vid->path}} ">
            <h5 class ="my-0 mr-md-0 font-weight-normal">Video</h5>
        </a>
        <h5 class ="my-0 mr-md-2 font-weight-normal">Results</h5>
        <a class ="my-0 mr-md-auto font-weight-normal" href="{{route('Trunc')}} ">
            <h5 class ="my-0 mr-md-0 font-weight-normal">Delete all messages</h5>
            </a>
        <nav class="my-0 mr-md-0 me-md-0">
            <a class="p-0 "  href="{{route('home')}}">
                <h5 class ="my-0 mr-md-0 font-weight-normal">Main page</h5>
                </a>
        </nav>
    </header>
    <div class="container">
    @foreach ($data as $el)
        <div class="alert alert-warning">
        <h5>Found № {{$el->id}} </h5>
        <p>{{$el->description}}</p>
        <p>{{$el->time}}</p>
        </div>
    @endforeach
    </div>
</body>
</html>

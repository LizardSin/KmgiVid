<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Results</title>
    <link rel="shortcut icon" href="img/up.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body style="background: #666666">
<div class="container text-center mt-2 mb-5 mw-100">
<div class="table-responsive">
    <table class ="table table-hover table-light table-bordered">
        <input class="form-control flex-fill mb-1" id="myInput" type="text" placeholder="Search...">
        <thead class ="thead-light">
        <tr>
            <th>Description</th>
            <th>At time</th>
            <th>Unique objects</th>
            <th>Confidence %</th>
        </tr>
        </thead>

        <tbody id="myTable">
        @foreach ($data as $el)
            <tr>



                <td>
                    <a class="navbar-brand text-dark" href="{{$el->path}}" target="_blank">
                        <p>
                            <u>
                            {{$el->description}}
                            </u>
                        </p>
                    </a>
                </td>
                <td>{{$el->time}}</td>
                <td>{{$el->quantity}}</td>
                <td>{{$el->confidence}}%</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</div>
<nav class="navbar fixed-bottom navbar-dark bg-dark" style="height: 45px">
    <a class="navbar-brand" href="{{route('Trunc')}}">
        <h5>
            Clear Database
        </h5>
    </a>
    <a class="navbar-brand" href="{{route('home')}}">
        <h5>
            Home
        </h5>
    </a>
</nav>
<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

</body>
</html>

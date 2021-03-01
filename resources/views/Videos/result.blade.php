@extends('videos.create2')
@section('title')
 Result
@endsection
@section('video-link')
    <div class="embed-responsive embed-responsive-16by9 border border-white border-5 rounded">
        <iframe class="embed-responsive-item" src="{{$video_url}}" allowfullscreen></iframe>
    </div>
    @endsection
@section('results')
<div class="table-responsive">
<table class ="table table-hover table-light table-bordered">
    <input class="form-control flex-fill mb-1" id="myInput" type="text" placeholder="Search...">
    <thead class ="thead-light">
        <tr>
            <th>Found №</th>
            <th>Description</th>
            <th>At time</th>
            <th>Unique objects</th>
            <th>Confidence %</th>
        </tr>
    </thead>

    <tbody id="myTable">
        @foreach ($data as $el)
            <tr>
                <td></td>
                <td>{{$el->description}}</td>
                <td>{{$el->time}}</td>
                <td>{{$el->quantity}}</td>
                <td>{{$el->confidence}}%</td>
                </tr>
        @endforeach
    </tbody>
</table>
</div>

    <script>
        $(function(){
            $('table td:first-child').each(function (i) {
                $(this).html(i+1);
            });
        });
    </script>
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
@endsection

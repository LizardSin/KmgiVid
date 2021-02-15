@extends('videos.create2')
@section('title')
 Result
@endsection
@section('video-link')
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="{{$video_url}}" allowfullscreen></iframe>
    </div>
    @endsection
@section('results')
<table class ="table table-hover table-bordered table-light" >
    <thead class ="thead-light">
        <tr>
            <th>Found №</th>
            <th>Description</th>
            <th>At time</th>
            <th>Confidence %</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $el)
            <tr>
                <td></td>
                <td>{{$el->description}}</td>
                <td>{{$el->time}}</td>
                <td>{{$el->confidence}}%</td>
                </tr>
        @endforeach
    </tbody>
</table>
    <script>
        $(function(){
            $('table td:first-child').each(function (i) {
                $(this).html(i+1);
            });
        });
    </script>
@endsection

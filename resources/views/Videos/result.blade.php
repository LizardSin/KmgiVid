@extends('videos.create2')
@section('title')
 Result
@endsection
@section('results')
    {{--@foreach ($data as $el)
        <div class="alert alert-warning text-left">
            <h5>Found</h5>
            <p>{{$el->description}}</p>
            <p>{{$el->time}}</p>
        </div>
    @endforeach--}}
<table class ="table table-hover table-bordered table-light" >
    @foreach ($data as $el)
<tr>
    <td></td>
    <td>{{$el->description}}</td>
    <td>{{$el->time}}</td>
    <td></td>
</tr>
    @endforeach
</table>
    <script>
        $(function(){
            $('table td:first-child').each(function (i) {
                $(this).html(i+1);
            });
        });
    </script>
@endsection

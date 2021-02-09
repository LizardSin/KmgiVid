<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function create()
    {
        return view('videos.create');
    }
    public function store(Request $request)
    {
        $path = $request->file('video')->store('videos', 's3');

        $video= Video::create([
            'name'=>basename($path),
            'path'=>Storage::disk('s3')->path($path)
            ]);

        return $video;
    }
    public function show(Video $video)
    {

    }
}

<?php

namespace App\Http\Controllers;

use Faker\Provider\Image;
use Illuminate\Http\Request;
use App\Models\Models\Video;

class VideoController extends Controller
{
    public function create()
    {
        return view('videos.create');
    }
    public function store(Request $request)
    {

    }
    public function show(Video $video)
    {

    }
}

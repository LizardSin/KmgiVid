<?php

namespace App\Http\Controllers;


use Aws\Rekognition\RekognitionClient;
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
            'path'=>Storage::disk('s3')->url($path)
            ]);


        $client = new RekognitionClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest'
        ]);
        $results = $client -> detectModerationLabels( ['Image'=>['S3Object'=>['Bucket'=>env('AWS_BUCKET'), 'Name'=>$path]]]);
        $results_labels = $results->get('ModerationLabels');



        return dd($results_labels) ;

    }
}

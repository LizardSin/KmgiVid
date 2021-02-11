<?php

namespace App\Http\Controllers;


use App\Models\videos_results;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use App\Models\Models\Video;
use Illuminate\Support\Facades\DB;
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
        $results = $client -> StartContentModeration( ['Video'=>['S3Object'=>['Bucket'=>env('AWS_BUCKET'), 'Name'=>$path]]]);
        $results_labels = $results->get('JobId');
        $content=$client -> GetContentModeration(['JobId'=>$results_labels]);
        $status = $content->get ('JobStatus');
        while($status == 'IN_PROGRESS'):
            $content=$client -> GetContentModeration(['JobId'=>$results_labels]);
            $status = $content->get ('JobStatus');
            sleep(3);
        endwhile;
        $content_labels = $content->get('ModerationLabels');
        foreach ($content_labels as $el):
            $moderation_label= $el['ModerationLabel'];
            $m_sec = $el['Timestamp'];
            $m_sec2 = $m_sec/60000;
            $minutes = intdiv($m_sec,60000);
            $seconds = round((fmod($m_sec2,1)*60),2);
            $time = $minutes . " min " . $seconds . " sec";
            $videos_results = videos_results:: create([
                'description'=>$moderation_label['Name'],
                'time'=> $time,
            ]);
        endforeach;

        return redirect()->route('results');
    }
    public function allData(){
        $result = new videos_results();
        $vid= new video();
        return view('videos.results', ['data'=>$result->all(), 'vid'=>$vid->all()->last()]);

    }
    public function Trunc(){
        DB::table('videos_results')->truncate();
        $result = new videos_results();
        $vid= new video();
        return view('videos.results', ['data'=>$result->all(), 'vid'=>$vid->all()->last()]);
    }
}

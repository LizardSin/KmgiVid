<?php

namespace App\Http\Controllers;


use App\Models\videos_results;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use App\Models\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class VideoController extends Controller
{
   /* public function try123(){
        $vid= new video();
        $result = new videos_results();
        $vid2=$vid->all('id')->last()->id;
        return $result->all()->where('video_id',$vid2);
}*/
   public function init(Request $request)
   {
       $path = $request->file('video')->store('videos', 's3');
       $video= Video::create([
           'name'=>basename($path),
           'path'=>Storage::disk('s3')->url($path)
       ]);

       if ($request->get('radio') == 'content'):
           return $this->ContentModeration($path);
       elseif($request->get('radio') == 'label'):
           return $this->LabelDetection($path) ;

       else: return $this->LabelDetection($path);
       endif;
   }
    public function ContentModeration($path)
    {

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
        $vid_id= DB::table('videos')->latest()->first()->id;
        foreach ($content_labels as $el):
            $moderation_label= $el['ModerationLabel'];
            $m_sec = $el['Timestamp'];
            $m_sec2 = $m_sec/60000;
            $minutes = intdiv($m_sec,60000);
            $seconds = round((fmod($m_sec2,1)*60),2);
            $time = $minutes . " min " . $seconds . " sec";

            $videos_results = videos_results:: create([
                'description'=>$moderation_label['Name'],
                'confidence'=>round($moderation_label['Confidence']),
                'time'=> $time,
                'video_id'=>$vid_id,
                'path'=>$path,
            ]);
        endforeach;

        $video_url=$vid= DB::table('videos')->latest()->first()->path;
        $result= DB::table('videos_results')->where('video_id', '=', $vid_id)->get();
        return view('videos.result',['data'=>$result, 'video_url'=>$video_url]);
    }
    public function LabelDetection($path){
        $client = new RekognitionClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest'
        ]);
        $results = $client -> StartLabelDetection( ['Video'=>['S3Object'=>['Bucket'=>env('AWS_BUCKET'), 'Name'=>$path]]]);
        $results_labels = $results->get('JobId');
        $content=$client -> GetLabelDetection(['JobId'=>$results_labels]);
        $status = $content->get ('JobStatus');
        while($status == 'IN_PROGRESS'):
            $content=$client -> GetLabelDetection(['JobId'=>$results_labels]);
            $status = $content->get ('JobStatus');
            sleep(3);
        endwhile;
        $content_labels = $content->get('Labels');
        $vid_id= DB::table('videos')->latest()->first()->id;
        $video_url= DB::table('videos')->latest()->first()->path;
        foreach ($content_labels as $el):
            $moderation_label= $el['Label'];
            if ($moderation_label['Name']=='Human' or $moderation_label['Name']=='Car'):
            $m_sec = $el['Timestamp'];
            $m_sec2 = $m_sec/60000;
            $minutes = intdiv($m_sec,60000);
            $seconds = round((fmod($m_sec2,1)*60),2);
            $time = $minutes . " min " . $seconds . " sec";

            $videos_results = videos_results:: create([
                'description'=>$moderation_label['Name'],
                'confidence'=>round($moderation_label['Confidence']),
                'time'=> $time,
                'video_id'=>$vid_id,
                'path'=>$video_url,
            ]);
            endif;
        endforeach;



        $result= DB::table('videos_results')->where('video_id', '=', $vid_id)->get();
        return  view('videos.result',['data'=>$result, 'video_url'=>$video_url]);
    }

    public function Trunc(){
        DB::table('videos_results')->truncate();
        DB::table('videos')->truncate();
        return redirect()->route('home');
    }
    public function AllResults(){
       $results=DB::table('videos_results')->latest()->get();

       return view('videos.results', ['data'=>$results]);
    }
}

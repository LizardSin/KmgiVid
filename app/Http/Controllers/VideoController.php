<?php

namespace App\Http\Controllers;


use App\Models\videos_results;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use App\Models\Models\Video;
use Illuminate\Support\Arr;
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
        $bw=0;
        $bh=0;
        $bl=0;
        $bt=0;

        foreach ($content_labels as $el):
            $moderation_label= $el['Label'];
            if ($moderation_label['Name']=='Person' or $moderation_label['Name']=='Car'):
                $instances=$moderation_label['Instances'];
                $bwArr=array();
                foreach($instances as $i):
                $box=$i['BoundingBox'];
                $box_width=$box['Width'];
                $box_width=round($box_width,1);
                $bwArr=Arr::prepend($bwArr,$box_width);
                $box_height=$box['Height'];
                $box_height=round($box_height,1);
                $box_left=$box['Left'];
                $box_left=round($box_left,1);
                $box_top=$box['Top'];
                $box_top=round($box_top,1);
                endforeach;
                /*if ($bw!=$box_width and $bh!=$box_height and $bl!=$box_left and $bt!=$box_top):*/
                    $m_sec = $el['Timestamp'];
                    $m_sec2 = $m_sec/60000;
                    $minutes = intdiv($m_sec,60000);
                    $seconds = round((fmod($m_sec2,1)*60),2);
                    $time = $minutes . " min " . $seconds . " sec";
                    $quantity= count($bwArr);

                    $videos_results = videos_results:: create([
                        'description'=>$moderation_label['Name'],
                        'confidence'=>round($moderation_label['Confidence']),
                        'time'=> $time,
                        'video_id'=>$vid_id,
                        'path'=>$video_url,
                        'quantity'=>$quantity,
                ]);
                /*endif;*/
                /*$bw=$box_width;
                $bh=$box_height;
                $bl=$box_left;
                $bt=$box_top;*/



            endif;
        endforeach;



        $result= DB::table('videos_results')->where('video_id', '=', $vid_id)->get();
        return view('videos.result',['data'=>$result, 'video_url'=>$video_url]);
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

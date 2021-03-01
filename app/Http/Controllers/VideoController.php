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

    public function init2(Request $request)
    {
        try {
            $path = $request->file('video')->store('videos', 's3');
            $video = Video::create([
                'name' => basename($path),
                'path' => Storage::disk('s3')->url($path)
            ]);
        } catch (\Exception $exception){
            dd($exception);
        };
        return $this->LabelDetection($path);
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
        $boxArr_check=array();
        $unique_splash=0;
        $iteration=0;
        $Person=0;
        $Car=0;
        foreach ($content_labels as $el):
            $moderation_label= $el['Label'];
            $q1=count($boxArr_check);
            if ($moderation_label['Name']=='Person' or $moderation_label['Name']=='Car'):
                if($moderation_label['Name']=='Person'):
                    $Person=$Person+1;
                    endif;
                if($moderation_label['Name']=='Car'):
                    $Car=$Car+1;
                endif;
                $instances=$moderation_label['Instances'];
                $boxArr=array();

                foreach($instances as $i):

                $box=$i['BoundingBox'];
                $box_width=$box['Width'];
                $box_width=round($box_width,2);
                    if($box_width<0.12):
                        $box_width=0;
                    endif;
                    if($box_width>=0.12 and $box_width<=0.295):
                        $box_width=0.2;
                    endif;
                    if($box_width>=0.296 and $box_width<=0.495):
                        $box_width=0.4;
                    endif;
                    if($box_width>=0.496 and $box_width<=0.695):
                        $box_width=0.6;
                    endif;
                    if($box_width>=0.696 and $box_width<=0.895):
                        $box_width=0.8;
                    endif;
                    if($box_width>=0.896):
                        $box_width=1;
                    endif;
                $box_height=$box['Height'];
                $box_height=round($box_height,2);
                    if($box_height<0.12):
                        $box_height=0;
                    endif;
                    if($box_height>=0.12 and $box_height<=0.295):
                        $box_height=0.2;
                    endif;
                    if($box_height>=0.296 and $box_height<=0.495):
                        $box_height=0.4;
                    endif;
                    if($box_height>=0.496 and $box_height<=0.695):
                        $box_height=0.6;
                    endif;
                    if($box_height>=0.696 and $box_height<=0.895):
                        $box_height=0.8;
                    endif;
                    if($box_height>=0.896):
                        $box_height=1;
                    endif;
                $box_left=$box['Left'];
                $box_left=round($box_left,2);
                    if($box_left<0.12):
                        $box_left=0;
                    endif;
                    if($box_left>=0.12 and $box_left<=0.295):
                        $box_left=0.2;
                    endif;
                    if($box_left>=0.296 and $box_left<=0.495):
                        $box_left=0.4;
                    endif;
                    if($box_left>=0.496 and $box_left<=0.695):
                        $box_left=0.6;
                    endif;
                    if($box_left>=0.696 and $box_left<=0.895):
                        $box_left=0.8;
                    endif;
                    if($box_left>=0.896):
                        $box_left=1;
                    endif;
                $box_top=$box['Top'];
                $box_top=round($box_top,2);
                    if($box_top<0.12):
                        $box_top=0;
                    endif;
                    if($box_top>=0.12 and $box_top<=0.295):
                        $box_top=0.2;
                    endif;
                    if($box_top>=0.296 and $box_top<=0.495):
                        $box_top=0.4;
                    endif;
                    if($box_top>=0.496 and $box_top<=0.695):
                        $box_top=0.6;
                    endif;
                    if($box_top>=0.696 and $box_top<=0.895):
                        $box_top=0.8;
                    endif;
                    if($box_top>=0.896):
                        $box_top=1;
                    endif;
                $boxArr=Arr::prepend($boxArr, [$box_width,$box_height,$box_left, $box_top]);
                endforeach;
                foreach ($boxArr as $e):
                    $true=in_array($e, $boxArr_check);
                if($true==false):
                    $boxArr_check=Arr::prepend($boxArr_check,$e);

                endif;
                endforeach;
                $bx=array();
                foreach ($boxArr_check as $l):
                    if(array_key_exists((array_search($l,$boxArr_check)+1),$boxArr_check)):
                        foreach (
                            range((array_search($l,$boxArr_check)+1),(count($boxArr_check))-1)as $number
                        ):
                            $width=$boxArr_check[$number][0];
                            $height=$boxArr_check[$number][1];
                            $left=$boxArr_check[$number][2];
                            $top=$boxArr_check[$number][3];
                            if (($l[0]==$width and $l[1]==$height and $l[2]==$left) or ($l[0]==$width and $l[2]==$left and $l[3]==$top)
                                or ($l[0]==$width and $l[1]==$height and $l[3]==$top) or ($l[1]==$height and $l[2]==$left and $l[3]==$top)
                            ):
                                array_push($bx, $boxArr_check[$number]);
                            endif;
                        endforeach;
                    endif;
                endforeach;
                foreach ($boxArr_check as $n):
                    if (in_array($n,$bx)):
                        $bumber=array_search($n,$boxArr_check);
                        unset($boxArr_check[$bumber]);
                    endif;
                endforeach;
                $boxArr_check=array_values($boxArr_check);
                $unique_splash=($unique_splash + count($boxArr_check));
                $iteration=$iteration+1;
                    if ($Person==1 or $Car==1):
                    $m_sec = $el['Timestamp'];
                    $m_sec2 = $m_sec/60000;
                    $minutes = intdiv($m_sec,60000);
                    $seconds = round((fmod($m_sec2,1)*60),2);
                    $time = $minutes . " min " . $seconds . " sec";
                    $quantity= count($boxArr_check);

                    $videos_results = videos_results:: create([
                        'description'=>$moderation_label['Name'],
                        'confidence'=>round($moderation_label['Confidence']),
                        'time'=> $time,
                        'video_id'=>$vid_id,
                        'path'=>$video_url,
                        'quantity'=>$quantity,
                        ]);
                        if($moderation_label['Name']=='Person'):
                            $Person=$Person+1;
                        endif;
                        if($moderation_label['Name']=='Car'):
                            $Car=$Car+1;
                        endif;
                    endif;
            endif;
        endforeach;


        $unique_splash=round(($unique_splash/$iteration));
        DB::table('videos_results')->where('video_id', '=', $vid_id)->update(['quantity'=>$unique_splash]);
        $result= DB::table('videos_results')->where('video_id', '=', $vid_id)->get();
        return view('videos.result',['data'=>$result, 'video_url'=>$video_url]) ;
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

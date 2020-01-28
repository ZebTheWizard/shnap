<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\FFMPEG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    public function upload(Request $r) {
        $disk = Storage::disk('spaces');
        $hash = hash("sha256", now());
        $output = $this->manipulateVideo($hash, $r->video_url, $r->audio_url);
        $file = $disk->put("shnap/".$hash, file_get_contents($output), ['visibility' => 'public']);
        if ($file) {
            $url = env("DO_SPACES_SUBDOMAIN") . '/shnap/' . $hash;
            $status = $this->makeTweet($output, $r->status);
            return response()->json($status);
        }
    }

    private function makeTweet($output, $status) {
        $conn = new TwitterOAuth(
            config('services.twitter.client_id'),
            config('services.twitter.client_secret'),
            auth()->user()->oauth_token,
            auth()->user()->oauth_token_secret
        );

        $media = $conn->upload("media/upload", [
            'media' => $output,
            'media_type' => 'video/mp4',
        ], true);

        if (isset($media->media_id_string)) {
            $result = $conn->post('statuses/update', [
                'status' => $status,
                'media_ids' => $media->media_id_string
            ]);
            return true;
        } else {
            return false;
        }
    }

    private function download($url, $name, $ext="") {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $contents = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($code != 200) {
            return false;
        }
        curl_close($ch);

        Storage::disk('local')->put($name.$ext, $contents);
        return storage_path("app/" . $name.$ext);
    }

    private function manipulateVideo($name, $video_url, $audio_url) {
        
        $output = storage_path("app/".$name."output.mp4");
        if ($video = $this->download($video_url, $name, "video.mp4")) {
            $ff = new FFMPEG;
            $ff->length("00:00:30")->input($video);

            if ($audio = $this->download($audio_url, $name, "audio.mp4")) {
                $ff->input($audio);
            }

            $res = $ff->copy()->run();
            if ($res["code"] === 0) {
                unlink($video);
                if ($audio) {
                    unlink($audio);
                }
                return $output;
            } else {
                dd($res);
            }
        }
    }
}

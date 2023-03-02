<?php

namespace App\Service\Agora;
use Illuminate\Support\Facades\Http;

class Recording 
{
   

    public function apiUrl($endpoint)
    {
        return "https://api.agora.io/v1/apps/" . config('services.agora.appId') . '/cloud_recording' . $endpoint;
    }

    /**
     * Generates Authorization Header for Agora Cloud Recording API
     * @return string|null
     */
    public function authorizationHeader()
    {
        $base64 = base64_encode(config('services.agora.customerId') . ":" . config('services.agora.customerSecret'));
        return "Basic " . $base64;
    }

    /**
     * Agora Cloud Recording Request,
     */
    public function agoraRequest()
    {
        return Http::withHeaders([
            'Authorization' => $this->authorizationHeader()
        ]);
    }

    /**
     * Returns Resources Id to starting recording.
     * @param $channelName Channel Name
     * @param $uid UserId
     * 
     * @return string|null
     */
    public function acquire($channel, $uid)
    {
        $body = [
            'cname' => $channel,
            'uid' => $uid,
            'clientRequest' => [
                'resourceExpiredHour' => 24
            ]
        ];
        
        $response = $this->agoraRequest()->post($this->apiUrl("/acquire"), $body);
        
        if($response->successful()) {
            return $response->json()["resourceId"];
        }
        
        return null;
    }

    /**
     * Start Recording
     * @param $resourceId 
     */
    public function start($channel, $uid, $token, $rid)
    {   
        $currentTime = date('Y-m-d_H:i:s', time());

        $response = $this->agoraRequest()->post($this->apiUrl("/resourceid/$rid/mode/mix/start"), [
            "cname" => $channel,
            "uid" => $uid,
            "clientRequest" => [
                "token" => $token,
                "recordingConfig" => [
                    "maxIdleTime" => 5,
                    "streamTypes" => 2, // both audio & video streams
                    "channelType" => 1, // Live broadcast profile
                    "transcodingConfig" => [
                        "width" => 1920,
                        "height" => 1080,
                        "fps" => 30,
                        "bitrate" => 6300
                    ]
                ], 
                "recordingFileConfig" => [
                    "avFileType" => ["hls", "mp4"]
                ],
                "storageConfig" => [
                    "vendor" => 1, // Agora Cloud Recording Rest API DOC
                    "region" => 1, // Agora Cloud Recording Rest API DOC
                    "accessKey" => config('services.aws.access_key'),
                    "bucket" => config('services.aws.bucket'),
                    "secretKey" => config('services.aws.secret_key'),
                    "fileNamePrefix" => [
                        config('setting.event_recording_folder_name')
                    ]
                ]
            ]
        ]);
        
        // dd($response->body());
        return $response->json();
        
    }

    public function stop($channel, $uid, $rid, $sid)
    {
        $response = $this->agoraRequest()->post($this->apiUrl("/resourceid/$rid/sid/$sid/mode/mix/stop"), [
            "cname" => $channel,
            "uid" => $uid,
            'clientRequest' => [
                'resourceExpiredHour' => 24
            ]
        ]);
        return $response->json();
    }

    public function status($rid, $sid)
    {
        $r = $this->agoraRequest()->get($this->apiUrl("/resourceid/$rid/sid/$sid/mode/mix/query"));

        return $r->json();
    }

}

// sid: d443f6926449088be958dda97c11733e


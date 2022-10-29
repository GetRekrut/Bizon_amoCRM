<?php

namespace App\Http\Controllers;

use App\Models\BizonReport;
use App\Models\WebinarParticipant;
use Illuminate\Http\Request;

class BizonHandleReportsController extends Controller
{
    public function handleReport(){

        $report = BizonReport::where('status', 'unset')->first();

        $participants = json_decode($report['report'], true);
        $participants = $participants['usersMeta'];

        $messages = json_decode($report['messages'], true);

        foreach ($participants as $participant){

            WebinarParticipant::create([
                'domain_amo' => $report->domain_amo,
                'webinar_id' => $report->webinar_id,
                'user_id' => $participant['chatUserId'],
                'view' => $participant['view'],
                'view_till' => $participant['viewTill'],
                'phone' => $participant['phone'] ?? null,
                'email' => $participant['email'] ?? null,
                'user_name' => $participant['username'] ?? null,
                'room_id' => $participant['roomid'],
                'room_title' => $report['room_title'] ?? null,
                'url' => $participant['url'] ?? null,
                'ip' => $participant['ip'] ?? null,
                'user_agent' => $participant['useragent'] ?? null,
                'created' => $participant['created'],
                'play_video' => $participant['playVideo'] ?? null,
                'finished' => $participant['finished'] ?? null,
                'messages_num' => $participant['messages_num'] ?? null,
                'buttons' => json_encode($participant['buttons']),
                'banners' => json_encode($participant['banners']),
                'city' => $participant['city'] ?? null,
                'region' => $participant['region'] ?? null,
                'country' => $participant['country'] ?? null,
            ]);

        }

        foreach ($messages as $user_id => $message){

            $participant = WebinarParticipant::where('user_id', $user_id)->first();
            $body_message = null;

            foreach ($message as $value){

                $body_message .= $value.'; ';
            }

            $participant->messages = $body_message;
            $participant->save();

        }

    }

}

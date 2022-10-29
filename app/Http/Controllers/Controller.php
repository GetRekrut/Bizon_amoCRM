<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $token_bot_tg = '1733271437:AAFDiwmKsT2qp_U0JYa8rMifFZql7ncbwvI';
    protected $chat_id_tg = '-602888899';

    public function sendTelegram($message)
    {
        $send_data = [
            'text' => $message,
            'chat_id' => $this->chat_id_tg,
        ];

        $result = $this->generateCurl('https://api.telegram.org/bot' . $this->token_bot_tg . '/sendMessage', $send_data);

        if ($result->ok) return true;
        else return false;
    }

    public function generateCurl(string $url, $data){

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
        ]);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result);
        return $result;
    }
}

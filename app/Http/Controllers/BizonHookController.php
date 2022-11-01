<?php

namespace App\Http\Controllers;

use App\Models\BizonHook;
use App\Models\BizonAccess;
use App\Models\BizonReport;
use Illuminate\Http\Request;
use slavkluev\Bizon365\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;


class BizonHookController extends BizonAccessController
{
    public function takeHookAstrology(Request $request){

        $request = $request->all();

        $hook = BizonHook::create([
            'domain_amo' => 'uoppoly_astrology',
            'event' => $request['event'],
            'room_id' => $request['roomid'],
            'webinar_id' => $request['webinarId'],
        ]);

        if ($hook) return true;
        else return false;
    }

    public function takeHookMatrix(Request $request){

        $request = $request->all();

        $hook = BizonHook::create([
            'domain_amo' => 'uoppoly_matrix',
            'event' => $request['event'],
            'room_id' => $request['roomid'],
            'webinar_id' => $request['webinarId'],
        ]);

        if ($hook) return true;
        else return false;
    }

    public function handleHooks(){

        $hooks = BizonHook::where('status', 'unset')->get();

        foreach ($hooks as $hook){

            try {
                //если в строке амо есть поддомен, нужно его обрезать, пример uoppoly_matrix
                $check_domain = stristr($hook['domain_amo'], '_', true);

                if ($check_domain)
                    $client = $this->getAccountBizon($check_domain);
                else
                    $client = $this->getAccountBizon($hook['domain_amo']);


                $webinar_api = $client->getWebinarApi();
                $webinar_id = $hook['webinar_id'];
                $webinar_report = $webinar_api->getWebinar($webinar_id);
            }
            catch (\Exception $e) {

                $error = $e->getMessage();
                $this->sendTelegram('Ошибка обработки хука bizon_hook_contr: ' . $error);
                Log::warning('Ошибка обработки хука bizon_hook_contr: '.$error);
            }

            BizonReport::create([
                'domain_amo' => $hook['domain_amo'],
                'webinar_id' => $webinar_report['report']['webinarId'],
                'report' => $webinar_report['report']['report'],
                'messages' => $webinar_report['report']['messages'],
                'custom_fields' => json_encode($webinar_report['customFields']),
                'room_title' => $webinar_report['room_title'],
            ]);

            $hook->status = 'set';
            $hook->save();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BizonAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use slavkluev\Bizon365\Client;
use Illuminate\Support\Facades\Log;


class BizonAccessController extends Controller
{
    public function addAccount(Request $request){

        $request = $request->all();

        BizonAccess::create([
            'domain_amo' => $request['domain_amo'],
            'login' => $request['login'],
            'password' => Crypt::encryptString($request['password']),
            'api_token' => Crypt::encryptString($request['api_token']),
        ]);
    }

    public function getAccountBizon(string $domain_amo){

        $client = null;

        try {

            $access_bizon = BizonAccess::where('domain_amo', $domain_amo)->first();

            $api_token = Crypt::decryptString($access_bizon->api_token);
            $client = new Client($api_token);

        }
        catch (\Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка поиска доступа bizon_access_cont: ' . $error);
            Log::warning('Ошибка поиска доступа bizon_access_cont: '.$error);
        }

        return $client;

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmoAccess;

class AmoAccessController extends Controller
{
    public function getAmoCRM(string $domain)
    {
        $account_info = AmoAccess::where('domain', $domain)->first();

        $ufee = \Ufee\Amo\Oauthapi::setInstance([
            'domain' => $account_info['domain'],
            'client_id' => $account_info['client_id'],
            'client_secret' => $account_info['client_secret'],
            'redirect_uri' => $account_info['redirect_url'],
        ]);

        try {
            $ufee = \Ufee\Amo\Oauthapi::getInstance($account_info['client_id']);

            $ufee->account->toArray();

        } catch (\Exception $exception) {

            $ufee->fetchAccessToken($account_info['code']);
        }

        return $ufee;
    }

    public function addAccount(Request $request){

        $request = $request->all();

        AmoAccess::create([
            'domain' => $request['domain'],
            'client_id' => $request['client_id'],
            'client_secret' => $request['client_secret'],
            'redirect_url' => $request['redirect_url'],
            'code' => $request['code'],
        ]);
    }

//    public function showAccount(Request $request){

//        $request = $request->all();
//        $domain = $request['domain'];
//
//        $ufee = $this->getAmoCRM($domain);

//        dd($ufee->account);
//        return $ufee;
//    }
}

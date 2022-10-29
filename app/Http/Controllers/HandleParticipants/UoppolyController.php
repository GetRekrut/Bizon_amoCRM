<?php

namespace App\Http\Controllers\HandleParticipants;

use App\Http\Controllers\AmoFunctionController;
use App\Models\AmoPipelineInfo;
use App\Models\WebinarParticipant;
use Illuminate\Http\Request;

class UoppolyController extends AmoFunctionController
{
    public function checkNewParticipant(){

//        $participants = WebinarParticipant::where('domain_amo', 'like', '%uoppoly%')->get();
        $participants = WebinarParticipant::where('status', 'unset')->take(2)->get();

        foreach ($participants as $participant){

            $info_pipeline = AmoPipelineInfo::where('name_pipeline', $participant['domain_amo'])->first();

            $amoCRM = $this->getAmoCRM(stristr($participant['domain_amo'], '_', true));

            $current_contact = $this->getCurrentContact($amoCRM, $participant['phone']);
            dd($current_contact);
        }
    }
}

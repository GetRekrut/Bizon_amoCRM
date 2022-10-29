<?php

namespace App\Http\Controllers\HandleParticipants;

use App\Http\Controllers\AmoFunctionController;
use App\Http\Controllers\Controller;
use App\Models\AmoPipelineInfo;
use App\Models\WebinarParticipant;
use Illuminate\Http\Request;

class LitellyController extends AmoFunctionController
{
    public function handleNewParticipant(){

        $collection_participants = WebinarParticipant::where
        (['status'     => 'unset',
          'domain_amo' => 'litelly'])->take(2)->get();

        foreach ($collection_participants as $participant){

//            dd($participant);

            $info_pipeline = AmoPipelineInfo::where('name_pipeline', $participant['domain_amo'])->first();

            $amoCRM = $this->getAmoCRM($participant['domain_amo']);

            $current_contact = $this->getCurrentContact($amoCRM, $participant['phone']);

            if (!$current_contact){

                $new_contact = $this->createContact($amoCRM, $info_pipeline, $participant);

                $this->addNote($new_contact, $participant);
            }
            else{

                $this->addNote($current_contact, $participant);
            }
//            dd($current_contact);
        }
    }

}

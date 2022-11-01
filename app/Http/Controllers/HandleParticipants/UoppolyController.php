<?php

namespace App\Http\Controllers\HandleParticipants;

use App\Http\Controllers\AmoFunctionController;
use App\Models\AmoPipelineInfo;
use App\Models\WebinarParticipant;
use Illuminate\Http\Request;

class UoppolyController extends AmoFunctionController
{
    public function __construct()
    {
        AmoFunctionController::__construct();
    }

    public function addNewParticipant(){

//        $participants = WebinarParticipant::where('domain_amo', 'like', '%uoppoly%')->get();
        $participants = WebinarParticipant::where('status', 'unset')->take(2)->get();

        foreach ($participants as $participant){

//            $info_pipeline = AmoPipelineInfo::where('name_pipeline', $participant['domain_amo'])->first();
            $info_pipeline = [
                'Зарег. но не был' => 52415620,
                'Был до 30 минут' => 52180447,
                'Был 30-70 минут' => 52180774,
                'Более 70 минут' => 52180777,
                'Клик по кнопке-банеру' => 52415683,
                'Досмотрел до конца' => 52415683,
                'pipeline_id' => 6000460,
                'resp_user_id' => 8121154
            ];

            $amoCRM = $this->getAmoCRM(stristr($participant['domain_amo'], '_', true));

            $current_contact = $this->getCurrentContact($amoCRM, $participant['phone']);

            if (!$current_contact){

                $new_contact = $this->createContact($amoCRM, $info_pipeline, $participant);
                $this->addNote($new_contact, $participant);
                $new_lead = $this->createLeadInContact($new_contact, $info_pipeline, $participant);
                $this->addTask($new_contact, $new_lead, time() + 7200);

            }
            else{

                $this->addNote($current_contact, $participant);

                $leads = $current_contact->leads;

                // если нашли лиды на контакте
                if ($leads){

                    $active_lead = false;

                    foreach ($leads->collection()->all() as $lead){

                        //если лид не закрыт
                        if ($lead->status_id != 142 and $lead->status_id != 143){

                            //если это воронка матрица
                            if ($lead->pipeline_id == $info_pipeline['pipeline_id']){

                                //если сделка находился в этапе до Заказ ГК
//                                if (($amoCRM->order_status == 'Завершен') or
//                                    ($amoCRM->order_status == 'Частично оплачен' and ($amoCRM->checkStatusesBefore($lead->status_id, 'Частично оплачен'))) or
//                                    ($amoCRM->checkStatusesBefore($lead->status_id))){
//
//                                    $amoCRM->changeStatusOfLead($lead, $amoCRM->status_id);
//                                    goto update;
//                                }
//                                else
                                goto update;

                            }
                            else break;

                            update:
                            $active_lead = $this->updateLead($lead, $participant);
                            $this->addTask($current_contact, $active_lead, time() + 7200);
                        }
                    }

                    if (!$active_lead){
                        //если активного лида не нашли
                        $new_lead = $this->createLeadInContact($current_contact, $info_pipeline, $participant);
                        $this->addTask($current_contact, $new_lead, time() + 7200);

                    }
                }
                else {
                    //лидов на контакте нет
                    $new_lead = $this->createLeadInContact($current_contact, $info_pipeline, $participant);
                    $this->addTask($current_contact, $new_lead, time() + 7200);

                }
            }

            $participant['status'] = 'set';
            $participant->save();
        }
    }

}

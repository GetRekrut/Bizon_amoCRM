<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AmoFunctionController extends AmoAccessController
{
    public $note_text = null;

    public function getInstance(){

        $contact = $this->getCurrentContact();
    }

    public function getCurrentContact($amoCRM, $phone)
    {
        try {
            $contacts = $amoCRM->contacts()->searchByPhone($phone);
            $currentContact = $contacts->first();

        } catch (\Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка поиска контакта bizon_amo_func_controller: ' . $error);
            Log::warning('Ошибка поиска контакта bizon_amo_func_controller: '.$error);
        }

        return $currentContact;
    }

    public function addNote ($entity, $participant)
    {
        $this->note_text = [
            'Информация об участнике',
            '----------------------',
            ' Имя : ' . $participant['user_name'],
            '----------------------',
            ' Телефон : ' . $participant['phone'],
            '----------------------',
            ' Почта : ' . $participant['email'],
            '----------------------',
            ' Город : ' . $participant['city'],
            '----------------------',
        ];
        $this->note_text = implode("\n", $this->note_text);

        $note = $entity->createNote( $type = 4 );
        $note->text = $this->note_text;
        $note->element_type = 1;
        $note->element_id = $entity->id;
        $note->save();
    }

    public function addTask (
        $contact,
        $lead,
        $expireTime,
        $text = 'Добавлен лид из Бизона!'
    )
    {
        $task = $lead->createTask( $type = 1 );
        $task->text = $text;
        $task->element_type = 2;
        $task->responsible_user_id = $contact->responsible_user_id;
        $task->complete_till_at = $expireTime;
        $task->element_id = $lead->id;
        $task->save();
    }

    public function createContact ($amoCRM, $info_pipeline, $participant)
    {
        try {

            $newContact = $amoCRM->contacts()->create();
            $newContact->responsible_user_id = $info_pipeline['resp_user_id'];
            $newContact->name = $participant['user_name'];
            $newContact->cf('Телефон')->setValue($participant['phone']);
            $newContact->cf('Email')->setValue($participant['email']);
            $newContact->save();

        } catch (Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка создания контакта func_bizon: '.$error);
//            $this->putLog('Ошибка создания контакта func_bizon'.$error,'tilda_courier');
        }

        return $newContact;
    }

    public function createLeadInContact ($contact)
    {
        try {

            $newLead = $contact->createLead();
            $newLead->responsible_user_id = $contact->responsible_user_id;
            $newLead->status_id = $this->getStatusId();
//            $newLead->pipeline_id = $this->pipeline_id;
            $newLead->attachTags(['Тильда', $this->tag]);
            $newLead->name = 'Проходит обучение';
//            $newLead->sale = $this->budget;
            $newLead->cf()->byId(445211)->setValue($this->city); //город
            $newLead->cf()->byId(445215)->setValue($this->age); //возраст
            $newLead->cf()->byId(1151137)->setValue($this->delivery_mode); //способ доставки
            $newLead->cf()->byId(1151393)->setValue($this->device); //устройство
//            $newLead->cf()->byId(394419)->setValue(str_replace('руб.', '', $this->left_cost_money));//осталось оплатить
//            $newLead->cf()->byId(958575)->setValue(date("d.m.Y"));//дата создания заказа
            $newLead->save();

        } catch (Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка создания сделки tilda_courier: '.$error);
            $this->putLog('Ошибка создания сделки tilda_courier'.$error,'tilda_courier');
        }

        return $newLead;
    }

    public function definitionStatusId(){


    }

}

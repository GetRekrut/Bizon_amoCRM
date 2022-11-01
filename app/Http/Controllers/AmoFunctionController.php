<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AmoFunctionController extends AmoAccessController
{
    public $note_text = null;
    public $time_view = null;
    public $time_view_till = null;

    public function __construct()
    {
        $this->time_view = 'Добавлен лид из Бизона!';
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

    public function addNote($entity, $participant)
    {
        $this->note_text = [
            'Информация об участнике',
            '----------------------',
            ' Вебинар : ' . $participant['webinar_id'],
            '----------------------',
            ' Время входа : ' . date("Y-m-d H:i:s", $participant['view']/1000),
            '----------------------',
            ' Время выхода : ' . date("Y-m-d H:i:s", $participant['view_till']/1000),
            '----------------------',
            ' Проведенное время : ' . $this->getTimeMinOnWeb($participant). ' мин.',
            '----------------------',
//            ' Клик по кнопке : ' . $participant['buttons'] ? 'Да' : 'Нет',
//            '----------------------',
//            ' Клик по банеру : ' . $participant['banners'] ? 'Да' : 'Нет',
//            '----------------------',
            ' Комментарии : ' . $participant['messages'],
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

    public function createContact($amoCRM, $info_pipeline, $participant)
    {
        try {

            $newContact = $amoCRM->contacts()->create();
            $newContact->responsible_user_id = $info_pipeline['resp_user_id'];
            $newContact->name = $participant['user_name'];
            $newContact->cf('Телефон')->setValue($participant['phone']);
            $newContact->cf('Email')->setValue($participant['email']);
            $newContact->save();

        } catch (\Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка создания контакта func_bizon: '.$error);
//            $this->putLog('Ошибка создания контакта func_bizon'.$error,'tilda_courier');
        }

        return $newContact;
    }

    public function createLeadInContact($contact, $info_pipeline, $participant)
    {
        try {

            $newLead = $contact->createLead();
            $newLead->responsible_user_id = $contact->responsible_user_id;
            $newLead->status_id = $this->getStatusId($participant, $info_pipeline); //FIX
            $newLead->pipeline_id = $info_pipeline['pipeline_id'];
            $newLead->attachTags(['Бизон', $participant['room_id']]);
            $newLead->name = 'Участник веба';
            $newLead->cf()->byId(959157)->setValue($participant['city']); //город
            $newLead->cf()->byId(959147)->setValue($participant['webinar_id']); //вебинар
            $newLead->cf()->byId(959153)->setValue(date("Y-m-d H:i:s", $participant['view']/1000)); //время с
            $newLead->cf()->byId(959155)->setValue(date("Y-m-d H:i:s", $participant['view_till']/1000)); //время до
            $newLead->cf()->byId(959151)->setValue($this->getTimeMinOnWeb($participant));
            $newLead->cf()->byId(959149)->setValue(date("Y-m-d", $participant['view']/1000));//дата веба
            $newLead->save();

        } catch (\Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка создания сделки func_bizon: '.$error);
//            $this->putLog('Ошибка создания сделки tilda_courier'.$error,'tilda_courier');
        }

        return $newLead;
    }

    public function updateLead($lead, $participant)
    {
        try {

//            $newLead = $contact->createLead();
//            $newLead->responsible_user_id = $contact->responsible_user_id;
//            $newLead->status_id = $this->getStatusId($participant, $info_pipeline); //FIX
//            $newLead->pipeline_id = $info_pipeline['pipeline_id'];
            $lead->attachTags(['Бизон', $participant['room_id']]);
            $lead->name = 'Участник веба';
            $lead->cf()->byId(959157)->setValue($participant['city']); //город
            $lead->cf()->byId(959147)->setValue($participant['webinar_id']); //вебинар
            $lead->cf()->byId(959153)->setValue(date("Y-m-d H:i:s", $participant['view']/1000)); //время с
            $lead->cf()->byId(959155)->setValue(date("Y-m-d H:i:s", $participant['view_till']/1000)); //время до
            $lead->cf()->byId(959151)->setValue($this->getTimeMinOnWeb($participant));
            $lead->cf()->byId(959149)->setValue(date("Y-m-d", $participant['view']/1000));//дата веба
            $lead->save();

        } catch (\Exception $e) {

            $error = $e->getMessage();
            $this->sendTelegram('Ошибка обновления сделки func_bizon: '.$error);
//            $this->putLog('Ошибка обновления сделки tilda_courier'.$error,'tilda_courier');
        }

        return $lead;
    }

    public function getStatusId($participant, $info_pipeline){

        $diff_time = ($participant['view_till'] - $participant['view'])/1000; // время с бизона приходит в миллисек, нужно перевести в сек
        $finished = $participant['finished'];
        $status_id = null;

        if ($diff_time >= 60){

            $diff_time = $diff_time/60;

            if ($finished)
                $status_id = $info_pipeline['Досмотрел до конца'];

            elseif ($diff_time < 30)
                $status_id = $info_pipeline['Был до 30 минут'];

            elseif ($diff_time > 29 and $diff_time < 71)
                $status_id = $info_pipeline['Был 30-70 минут'];

            elseif ($diff_time > 70)
                $status_id = $info_pipeline['Более 70 минут'];

        }
        else $status_id = $info_pipeline['Был до 30 минут'];

        return $status_id;
    }

    public function getTimeMinOnWeb($participant){

        $diff_time = ($participant['view_till'] - $participant['view'])/1000; // время с бизона приходит в миллисек, нужно перевести в сек
        $diff_time = round($diff_time/60, 1);

        return $diff_time;
    }



}

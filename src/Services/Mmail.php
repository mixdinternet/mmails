<?php

namespace Mixdinternet\Mmails\Services;

use App\Exceptions\AdmixException;
use Illuminate\Support\Facades\Mail;
use Mixdinternet\Mmails\Mmail as MmailModel;

class Mmail
{
    public function send($data, $slug = null, $from = [], $template = 'mixdinternet/admix::emails.default', $attachments = [])
    {
        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::send(
            $template,
            $data,
            $this->getMaiable($this->findSlug($slug), $this->convertFrom($from), $attachments)
        );
    }

    public function queue($data, $slug = null, $from = [], $template = 'mixdinternet/admix::emails.default', $attachments = [])
    {
        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::queue(
            $template,
            $data,
            $this->getMaiable($this->findSlug($slug), $this->convertFrom($from), $attachments)
        );
    }

    public function later($seconds, $data, $slug = null, $from = [], $template = 'mixdinternet/admix::emails.default', $attachments = [])
    {
        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::later(
            $seconds,
            $template,
            $data,
            $this->getMaiable($this->findSlug($slug), $this->convertFrom($from), $attachments)
        );
    }

    protected function findSlug($slug)
    {
        $query = MmailModel::active();

        if ($slug) {
            $query->where('slug', $slug);
        }

        $db = $query->first();

        if (!$db) {
            throw new AdmixException('Formulário não configurado no administrativo');
        }

        return $db;
    }

    protected function toArray($emails)
    {
        return explode(',', str_replace(' ', '', $emails));
    }

    protected function convertFrom($from)
    {
        $convertedFrom['email'] = isset($from[0]) ? $from[0] : config('mail.from.address');
        $convertedFrom['name'] = isset($from[1]) ? $from[1] : config('mail.from.name');

        return $convertedFrom;
    }

    protected function getAttachmentsOnStorage(array $attachments = [])
    {
        $finalPath = storage_path('attachments/' . date('Y/m/d/'));
        @mkdir($finalPath, 0775, true);//cria o diretorio caso nao exista
        $attachmentsFromStorage = [];
        foreach ($attachments as $name => $options) {
            $file = request()->file($name);

            //verifica se o arquivo é valido
            if (request()->hasFile($name) && $file->isValid()) {
                $attachmentFromStorage['name'] = $finalPath . $file->getClientOriginalName();
                $attachmentFromStorage['as'] = isset($options['as']) ? $options['as'] : $file->getClientOriginalName();
                $attachmentFromStorage['mime'] = isset($options['mime']) ? $options['mime'] : $file->getMimeType();
                $file->move($finalPath, $file->getClientOriginalName());//copia o arquivo para a pasta storage
                $attachmentsFromStorage[] = $attachmentFromStorage;
            }

        }

        return $attachmentsFromStorage;
    }

    protected function getMaiable($db, $from, array $attachments = [])
    {
        $attachmentsFromStorage = $this->getAttachmentsOnStorage($attachments);
        return function ($message) use ($db, $from, $attachmentsFromStorage) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->subject($db->subject);
            $message->to($db->to, $db->toName);
            $message->replyTo($from['email'], $from['name']);

            if ($db->cc) {
                $message->cc($this->toArray($db->cc));
            }

            if ($db->bcc) {
                $message->bcc($this->toArray($db->bcc));
            }

            foreach ($attachmentsFromStorage as $attachmentFromStorage) {
                $message->attach(
                    $attachmentFromStorage['name'],
                    [
                        'as' => $attachmentFromStorage['as'],
                        'mime' => $attachmentFromStorage['mime'],
                    ]
                );
            }

        };
    }
}

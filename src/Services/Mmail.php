<?php

namespace Mixdinternet\Mmails\Services;

use App\Exceptions\AdmixException;
use Mixdinternet\Mmails\Mmail as MmailModel;
use Mail;

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
        $convertedFrom['email'] = isset($from[0]) ? $from[0] : 'noreply@mixd.com.br';
        $convertedFrom['name'] = isset($from[1]) ? $from[1] : 'MIXD Internet';
        return $convertedFrom;
    }

    protected function getAttachmentsOnStorage(array $attachments = [])
    {
        $finalPath = storage_path('attachments/' . date('d/m/Y/'));
        @mkdir($finalPath, 775);//cria o diretorio caso nao exista
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
            $message->from($from['email'], $from['name']);
            $message->subject($db->subject);
            $message->to($db->to, $db->toName);

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

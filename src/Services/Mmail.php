<?php

namespace Mixdinternet\Mmails\Services;

use App\Exceptions\AdmixException;
use Mixdinternet\Mmails\Mmail as MmailModel;
use Mail;

class Mmail
{
    public function send($data, $slug = null, $from = [], $template = 'emails.default')
    {
        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::send($template, $data, $this->getMaiable($this->findSlug($slug), $this->convertFrom($from)));
    }

    public function queue($data, $slug = null, $from = [], $template = 'emails.default')
    {
        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::queue($template, $data, $this->getMaiable($this->findSlug($slug), $this->convertFrom($from)));
    }

    public function later($seconds, $data, $slug = null, $from = [], $template = 'emails.default')
    {
        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::later($seconds, $template, $data, $this->getMaiable($this->findSlug($slug), $this->convertFrom($from)));
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

    protected function getMaiable($db, $from)
    {
        return function ($message) use ($db, $from) {
            $message->from($from['email'], $from['name']);
            $message->subject($db->subject);
            $message->to($db->to, $db->toName);
            if ($db->cc) {
                $message->cc($this->toArray($db->cc));
            }
            if ($db->bcc) {
                $message->bcc($this->toArray($db->bcc));
            }
        };
    }
}

<?php

namespace Mixdinternet\Mmails\Services;

use App\Exceptions\AdmixException;
use Mixdinternet\Mmails\Mmail as MmailModel;
use Mail;

class Mmail
{
    public function __construct()
    {

    }

    public function send($data, $slug = null, $from = [], $template = 'mixdinternet/admix::emails.default')
    {
        $db = $this->findSlug($slug);

        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::send($template, $data, function ($message) use ($db, $from) {
            $convertedFrom = $this->convertFrom($from);
            $message->from($convertedFrom['email'], $convertedFrom['name']);
            $message->subject($db->subject);
            $message->to($db->to, $db->toName);
            if ($db->cc) {
                $message->cc($this->toArray($db->cc));
            }
            if ($db->bcc) {
                $message->bcc($this->toArray($db->bcc));
            }
        });
    }

    public function queue($data, $slug = null, $from = [], $template = 'mixdinternet/admix::emails.default')
    {
        $db = $this->findSlug($slug);

        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::queue($template, $data, function ($message) use ($db, $from) {
            $convertedFrom = $this->convertFrom($from);
            $message->from($convertedFrom['email'], $convertedFrom['name']);
            $message->subject($db->subject);
            $message->to($db->to, $db->toName);
            if ($db->cc) {
                $message->cc($this->toArray($db->cc));
            }
            if ($db->bcc) {
                $message->bcc($this->toArray($db->bcc));
            }
        });
    }

    public function later($seconds, $data, $slug = null, $from = [], $template = 'mixdinternet/admix::emails.default')
    {
        $db = $this->findSlug($slug);

        $data = (is_array($data)) ? $data : ['content' => $data];
        return Mail::later($seconds, $template, $data, function ($message) use ($db, $from) {
            $convertedFrom = $this->convertFrom($from);
            $message->from($convertedFrom['email'], $convertedFrom['name']);
            $message->subject($db->subject);
            $message->to($db->to, $db->toName);
            if ($db->cc) {
                $message->cc($this->toArray($db->cc));
            }
            if ($db->bcc) {
                $message->bcc($this->toArray($db->bcc));
            }
        });
    }

    private function findSlug($slug)
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

    private function toArray($emails)
    {
        return explode(',', str_replace(' ', '', $emails));
    }

    private function convertFrom($from)
    {
        $convertedFrom['email'] = isset($from[0]) ? $from[0] : 'noreply@mixd.com.br';
        $convertedFrom['name'] = isset($from[1]) ? $from[1] : 'MIXD Internet';

        return $convertedFrom;
    }
}
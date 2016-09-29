<?php

namespace Mixdinternet\Mmails;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Venturecraft\Revisionable\RevisionableTrait;

class Mmail extends Model
{
    use SoftDeletes, Sluggable, RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $revisionFormattedFieldNames = [
        'name' => 'nome'
        , 'to' => 'para'
        , 'toName' => 'para (nome)'
        , 'cc' => 'cópia'
        , 'bcc' => 'cópia oculta'
        , 'subject' => 'assunto'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'status', 'name', 'to', 'toName', 'cc', 'bcc', 'subject'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function scopeSort($query, $fields = [])
    {
        if (count($fields) <= 0) {
            $fields = [
                'status' => 'asc'
                , 'name' => 'asc'
            ];
        }

        if (request()->has('field') && request()->has('sort')) {
            $fields = [request()->get('field') => request()->get('sort')];
        }

        foreach ($fields as $field => $order) {
            $query->orderBy($field, $order);
        }
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active')->sort();
    }

    # revision
    public function identifiableName()
    {
        return $this->name;
    }
}
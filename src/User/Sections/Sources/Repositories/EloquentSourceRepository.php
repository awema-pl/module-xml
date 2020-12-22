<?php

namespace AwemaPL\Xml\User\Sections\Sources\Repositories;

use AwemaPL\Xml\User\Sections\Sources\Models\Source;
use AwemaPL\Xml\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Xml\User\Sections\Sources\Scopes\EloquentSourceScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentSourceRepository extends BaseRepository implements SourceRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Source::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentSourceScopes($request))->scope($this->entity);
        $this->with('sourceable');
        return $this;
    }

}

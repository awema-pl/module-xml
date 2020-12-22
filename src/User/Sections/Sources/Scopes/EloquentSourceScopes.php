<?php

namespace AwemaPL\Xml\User\Sections\Sources\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentSourceScopes extends ScopesAbstract
{
    protected $scopes = [
    'q' =>SearchSource::class,
    ];
}

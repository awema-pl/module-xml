<?php

namespace AwemaPL\Xml\User\Sections\Sources\Repositories\Contracts;

use Illuminate\Http\Request;

interface SourceRepository
{

    /**
     * Scope source
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);

}

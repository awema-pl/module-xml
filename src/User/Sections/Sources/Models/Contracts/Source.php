<?php

namespace AwemaPL\Xml\User\Sections\Sources\Models\Contracts;

interface Source
{
    /**
     * Get the owning sourceable model.
     */
    public function sourceable();
}

<?php

namespace AwemaPL\Xml\Client\Readers\Requests\Contracts;

use AwemaPL\Xml\Client\Readers\XmlParserException;

interface Ceneo
{

    /**
     * All
     *
     * @return Generator
     * @throws XmlParserException
     */
    public function all();

    /**
     * Fail
     *
     * @return string
     */
    public function fail();
}


<?php

namespace AwemaPL\Xml\Client\Readers\Requests\Contracts;

use AwemaPL\Xml\Client\Readers\XmlParserException;

interface Ceneo
{

    /**
     * Each
     *
     * @param callable $callback First argument is XML string, set return callable true for break read
     * @param array $options
     * @throws XmlParserException
     */
    public function each(callable $callback, $options =[]);

    /**
     * Fail
     *
     * @return string
     */
    public function fail();
}


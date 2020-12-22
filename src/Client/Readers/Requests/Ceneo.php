<?php

namespace AwemaPL\Xml\Client\Readers\Requests;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Xml\Client\Readers\Reader;
use AwemaPL\Xml\Client\Readers\Requests\Contracts\Ceneo as CeneoContract;
use AwemaPL\Xml\Client\Readers\XmlParserException;

class Ceneo extends Reader implements CeneoContract
{
    /** @var string $startTag */
    protected $startTag = 'o';

    /**
     * Each
     *
     * @param callable $callback First argument is XML string, set return callable true for break read
     * @param array $options
     * @throws XmlParserException
     */
    public function each(callable $callback, $options = [])
    {
        parent::each($callback, $options);
    }

    /**
     * Fail
     *
     * @return string
     * @throws XmlParserException
     */
    public function fail()
    {
        try {
            $name = null;
            $this->each(function($xml) use (&$name) {
                $xmlObject = simplexml_load_string($xml);
                $name = trim((string) $xmlObject->name);
            }, ['limit' =>1]);
            if (!$name){
                return _p('xml::exceptions.client.not_complete_xml', 'XML error. Not complete XML file.');
            }
            return '';
        }catch (PrestashopApiException $e){
            return $e->getErrorUserMessage() ?? $e->getErrorMessage();
        }
    }
}

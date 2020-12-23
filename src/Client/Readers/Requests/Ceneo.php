<?php

namespace AwemaPL\Xml\Client\Readers\Requests;

use AwemaPL\BaseJS\Exceptions\PublicException;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Xml\Client\Readers\Reader;
use AwemaPL\Xml\Client\Readers\Requests\Contracts\Ceneo as CeneoContract;
use AwemaPL\Xml\Client\Readers\Responses\Response;
use AwemaPL\Xml\Client\Readers\XmlParserException;
use XMLReader;

class Ceneo extends Reader implements CeneoContract
{
    /** @var string $startTag */
    protected $startTag = 'o';

    /**
     * All
     *
     * @return Generator
     * @throws XmlParserException
     */
    public function all(){
        /** @var Response $response */
        foreach (parent::all() as $response){
            yield $response;
        }
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
            $isValid = null;
            /** @var Response $response */
            foreach ($this->all() as $response){
                $isValid = $response->exist('//@id')
                    && $response->exist('//@price')
                    && $response->exist('//@stock')
                    && $response->exist('//name')
                    && $response->exist('//cat');
                break;
            }
            if (!$isValid) {
                return _p('xml::exceptions.client.invalid_xml_structure', 'XML error. Invalid XML structure.');
            }
            return '';
        } catch (PrestashopApiException $e) {
            return $e->getErrorUserMessage() ?? $e->getErrorMessage();
        }
    }
}

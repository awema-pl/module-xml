<?php

namespace AwemaPL\Xml\Client;

use AwemaPL\Xml\Client\Contracts\XmlClient as XmlClientContract;
use AwemaPL\Xml\Client\Readers\Requests\Ceneo;
use AwemaPL\Xml\Client\Readers\Requests\Contracts\Ceneo as CeneoContract;

class XmlClient implements XmlClientContract
{
    /** @var Config $config */
    private $config;

    public function __construct(array $parameters)
    {
        $this->config = new Config($parameters);
    }

    /**
     * Ceneo
     *
     * @return CeneoContract
     */
    public function ceneo()
    {
        return new Ceneo($this->config);
    }
}

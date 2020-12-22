<?php
namespace AwemaPL\Xml\Client\Contracts;

use AwemaPL\Xml\Client\Readers\Requests\Contracts\Ceneo as CeneoContract;

interface XmlClient
{
    /**
     * Ceneo
     *
     * @return CeneoContract
     */
    public function ceneo();
}

<?php

namespace AwemaPL\Xml\Client\Readers;
use AwemaPL\BaseJS\Exceptions\PublicException;

class XmlParserException extends PublicException
{
    const ERROR_DOWNLOAD_XML = 'ERROR_DOWNLOAD_XML';
    const ERROR_OPEN_XML = 'ERROR_OPEN_XML';
    const ERROR_READ_XML = 'ERROR_READ_XML';
    const ERROR_PARSE_XML = 'ERROR_PARSE_XML';
    const ERROR_EMPTY_XML = 'ERROR_EMPTY_XML';
}

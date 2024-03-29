<?php
namespace AwemaPL\Xml\Client\Readers\Responses;

use AwemaPL\Xml\Client\Readers\XmlParserException;
use Illuminate\Support\Arr;
use SimpleXMLElement;
use XMLReader;
use DOMNode;
use Exception;

class Response
{
    /** @var SimpleXMLElement $xml */
    private $xml;

    /** @var XMLReader $reader */
    private $reader;

    /** @var string $content */
    private $content;

    /** @var array $array */
    private $array;

    public function __construct($reader)
    {
        $this->reader = $reader;
    }

    /**
     * Return the xml as `string`.
     *
     * @return string
     */
    public function content(): string
    {
        if (!$this->content) {
            try {
                $this->content = $this->reader->readOuterXml();
            }catch (Exception $e){
                throw new XmlParserException('Error parse XML. '. $e->getMessage(), XmlParserException::ERROR_PARSE_XML, 409, $e,
                    _p('xml::exceptions.client.error_parse_xml', 'XML error. The XML source could not be parsed.'), null, false);
            }
        }
        return $this->content;
    }

    /**
     * Return the xml as `object`.
     *
     * @return SimpleXMLElement
     */
    public function xml(): SimpleXMLElement
    {
        if (!$this->xml) {
            $this->xml = $this->parseXML($this->content());
        }
        return $this->xml;
    }

    /**
     * Return XML reader
     *
     * @return XMLReader
     */
    public function reader(): XMLReader{
        return $this->reader;
    }

    /**
     * Parse XML
     *
     * @param string $content String from a CURL response
     * @return SimpleXMLElement status_code, response
     * @throws PrestashopApiException
     */
    protected function parseXML($content)
    {
        if ($content != '') {
            libxml_clear_errors();
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (libxml_get_errors()) {
                $msg = var_export(libxml_get_errors(), true);
                libxml_clear_errors();
                if (!$suppressExceptions) {
                    throw new XmlParserException('Error parse XML. ', XmlParserException::ERROR_PARSE_XML, 409, null,
                        _p('xml::exceptions.client.error_parse_xml', 'XML error. The XML source could not be parsed.'), null, false);
                }
            }
            return $xml;
        }
        else {
            throw new XmlParserException('Error parse XML. Content not detected while parsing XML.', XmlParserException::ERROR_EMPTY_XML, 409, null,
                _p('xml::exceptions.client.error_parse_no_content', 'XML error. Content not detected while parsing XML.'), null, false);
        }
    }

    /**
     * Return string from XML
     *
     * @param $xpath
     * return string
     */
    public function getString($xpath){
        return trim((string) ($this->xml()->xpath($xpath)[0] ?? ''));
    }

    /**
     * Return integer from XML
     *
     * @param $xpath
     * return int
     */
    public function getInteger($xpath){
        return trim((string) ($this->xml()->xpath($xpath)[0] ?? ''));
    }

    /**
     * Return float from XML
     *
     * @param $xpath
     * return float
     */
    public function getFloat($xpath){
        return (float) ($this->xml()->xpath($xpath)[0] ?? 0);
    }

    /**
     * Return boolean from XML
     *
     * @param $xpath
     * return bool
     */
    public function getBoolean($xpath){
        return (bool) ($this->xml()->xpath($xpath)[0] ?? false);
    }

    /**
     * Return array from XML
     *
     * @param $xpath
     * @return array
     */
    public function getArray($xpath)
    {
        $array = json_decode(json_encode($this->xml()->xpath($xpath), JSON_UNESCAPED_UNICODE), true, 512, JSON_UNESCAPED_UNICODE);
        $hasOneItem = !isset(array_values($array)[0][0]);
        return ($hasOneItem) ? array_values($array) : array_values($array)[0];
    }

    /**
     * Return exist in XML
     *
     * @param $xpath
     * return bool
     */
    public function exist($xpath){
        $xml = $this->xml()->xpath($xpath);
        if (sizeof($xml)>1){
            return true;
        }
        $elementXml =$this->xml()->xpath($xpath)[0] ?? null;
        if ($elementXml){
            $value = (string) $elementXml;
            $value = trim($value);
            return $value !== '';
        }
        return false;
    }

}

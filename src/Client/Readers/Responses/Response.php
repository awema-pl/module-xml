<?php
namespace AwemaPL\Xml\Client\Readers\Responses;

use AwemaPL\Xml\Client\Readers\XmlParserException;
use Illuminate\Support\Arr;
use SimpleXMLElement;
use XMLReader;
use DOMNode;

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
            $content = $this->reader->readOuterXml();
            $this->content = $this->convertEntities($content);
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
            $this->xml = $this->parseXMLToDom($this->content());
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
     * To array
     *
     * @return array
     */
    public function toArray()
    {
        if (!$this->array) {
            $this->array = $this->parseXmlToArray($this->reader()->expand());
        }
        return $this->array;
    }

    /**
     * Parse XML to array
     *
     * @param XMLReader $oXml
     * @return array
     */
    protected function parseXmlToArray(DOMNode $node) {

            $output =[];
            switch ($node->nodeType) {
                case XML_CDATA_SECTION_NODE:
                case XML_TEXT_NODE:
                    $output = trim($node->textContent);
                    break;
                case XML_ELEMENT_NODE:
                    for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                        $child = $node->childNodes->item($i);
                        $v = $this->parseXmlToArray($child);
                        if(isset($child->tagName)) {
                            $t = $child->tagName;
                            if(!isset($output[$t])) {
                                $output[$t] = [];
                            }
                            $output[$t][] = $v;
                        }
                        elseif($v || $v === '0') {
                            $output = (string) $v;
                        }
                    }
                    if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                        $output = array('@content'=>$output); //Change output into an array.
                    }
                    if(is_array($output)) {
                        if($node->attributes->length) {
                            $a = array();
                            foreach($node->attributes as $attrName => $attrNode) {
                                $a[$attrName] = (string) $attrNode->value;
                            }
                            $output['@attributes'] = $a;
                        }
                        foreach ($output as $t => $v) {
                            if(is_array($v) && count($v)==1 && $t!='@attributes') {
                                $output[$t] = $v[0];
                            }
                        }
                    }
                    break;
            }
            return $output;
    }

    /**
     * Parse XML to Dom
     *
     * @param string $response String from a CURL response
     * @return SimpleXMLElement status_code, response
     * @throws PrestashopApiException
     */
    protected function parseXMLToDom($response)
    {
        if ($response != '') {
            libxml_clear_errors();
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (libxml_get_errors()) {
                $msg = var_export(libxml_get_errors(), true);
                libxml_clear_errors();
                if (!$suppressExceptions) {
                    throw new XmlParserException('Error parse XML. '. $e->getMessage(), XmlParserException::ERROR_PARSE_XML, 409, $e,
                        _p('xml::exceptions.client.error_parse_xml', 'XML error. The XML source could not be parsed.'), null, false);
                }
            }
            return $xml;
        }
        else {
            throw new XmlParserException('Error parse XML. Content not detected while parsing XML.'. $e->getMessage(), XmlParserException::ERROR_EMPTY_XML, 409, $e,
                _p('xml::exceptions.client.error_parse_no_content', 'XML error. Content not detected while parsing XML.'), null, false);
        }
    }

    /**
     * Return the provided parameter's value from the response's JSON.
     *
     * @param string $parameter
     * @param $default
     * @return mixed
     */
    public function getParameter(string $parameter, $default=null)
    {
        return Arr::get($this->toArray(), $key, $default);
    }

    /**
     * Replaces all html entities into its original symbols.
     *
     * @param string $content
     * @return string
     */
    private function convertEntities($content)
    {
        $table = array_map('utf8_encode', array_flip(
            array_diff(
                get_html_translation_table(HTML_ENTITIES),
                get_html_translation_table(HTML_SPECIALCHARS)
            )
        ));
        return preg_replace('/&#[\d\w]+;/', '', strtr($content, $table));
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
        return !empty(trim((string) ($this->xml()->xpath($xpath)[0] ?? '')));
    }

}

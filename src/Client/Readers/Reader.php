<?php

namespace AwemaPL\Xml\Client\Readers;
use AwemaPL\Xml\Client\Config;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use XMLReader;

class Reader
{
    /** @var string $startTag */
    protected $startTag;

    /** @var Config $config */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Each
     *
     * @param callable $callback First argument is XML string, set return callable true for break read
     * @param array $options
     * @throws XmlParserException
     */
    public function each(callable $callback, $options =[]){
        $xmlFiletemp = $this->getXmlFiletemp();
        $reader = $this->getReader($xmlFiletemp);
        $counter = 0;
        try {
            while ($reader->read()) {
                if ($reader->nodeType == XMLReader::ELEMENT && (!$this->startTag || $reader->name == $this->startTag)) {
                    $xml = $reader->readOuterXML();
                    $interrupt = $callback($xml);
                    $counter++;
                    if ($interrupt || ($options['limit'] ?? null && $counter >= $options['limit'])){
                        break;
                    }
                }
            }
            $reader->close();
        } catch (Exception $e){
            throw new XmlParserException('Error parse XML. '. $e->getMessage(), XmlParserException::ERROR_PARSE_XML, 409, $e,
                _p('xml::exceptions.client.error_parse_xml', 'XML error. The XML source could not be read.'), null, false);
        } finally {
            $this->removeXmlFiletemp($xmlFiletemp);
        }
    }

    /**
     * Get reader
     *
     * @param $xmlFiletemp
     * @param array $options
     * @return XMLReader
     */
    protected function getReader($xmlFiletemp)
    {
        $reader = new XMLReader();
        $url = $this->config->getUrl();
        if ($this->config->isDownloadBefore()){
            try{
                $client = new Client(['verify' => $this->config->isVerifySsl()]);
                $client->request('GET', $this->config->getUrl(), [
                    'sink' => $xmlFiletemp,
                    'headers' => [
                        'User-Agent' => $this->config->getUserAgent(),
                    ]
                ]);
            } catch (Exception $e){
                throw new XmlParserException('Error download XML. '. $e->getMessage(), XmlParserException::ERROR_DOWNLOAD_XML, 409, $e,
                    _p('xml::exceptions.client.error_download_xml', 'XML error. The XML source could not be downloaded.'), null, false);
            }
            $url = $xmlFiletemp;
        }
        try{
            $reader->open($url, 'UTF-8', LIBXML_PARSEHUGE);
        } catch (Exception $e){
            throw new XmlParserException('Error open XML. '. $e->getMessage(), XmlParserException::ERROR_OPEN_XML, 409, $e,
                _p('xml::exceptions.client.error_open_xml', 'XML error. The XML source could not be opened.'), null, false);
        }
        return $reader;
    }

    /**
     * Get XML file temp
     *
     * @param string $nameFile
     * @return mixed
     */
    private function getXmlFiletemp()
    {
        $uuid = Uuid::uuid4();
        $path = Storage::disk('local')->path("/modules/xml/temp/{$uuid}.xml");
        $dirname = dirname($path);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }
        return $path;
    }

    /**
     * Remove XML file temporary
     *
     * @param $xmlFiletemp
     */
    private function removeXmlFiletemp($xmlFiletemp)
    {
        if (Storage::exists($xmlFiletemp)){
            Storage::delete($xmlFiletemp);
        }
    }

}

<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Services;

use AwemaPL\Xml\User\Sections\Ceneosources\Services\Contracts\DataExtractor as DataExtractorContract;
use SimpleXMLElement;

class DataExtractor implements DataExtractorContract
{
    /**
     * Get ID
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getId(SimpleXMLElement $xml): string
    {
        return trim((string)$xml['id']);
    }

    /**
     * Get brutto price
     *
     * @param SimpleXMLElement $xml
     * @return float
     */
    public function getBruttoPrice(SimpleXMLElement $xml): float
    {
        return (float)$xml['price'];
    }

    /**
     * Get stock
     *
     * @param SimpleXMLElement $xml
     * @return int
     */
    public function getStock(SimpleXMLElement $xml): int
    {
        return (int)$xml['stock'];
    }

    /**
     * Get availability
     *
     * @param SimpleXMLElement $xml
     * @return int
     */
    public function getAvailability(SimpleXMLElement $xml): int
    {
        return (int)$xml['avail'];
    }


    /**
     * Get name
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getName(SimpleXMLElement $xml): string
    {
        return trim((string)$xml->name);
    }

    /**
     * Get cat
     *
     * @param SimpleXMLElement $xml
     * @param string $divider
     * @return string
     */
    public function getCat(SimpleXMLElement $xml, string $divider = '/'):string
    {
        $cat = '';
        $crumbs = trim((string)$xml->cat);
        foreach (explode('/', $crumbs) as $crumb){
            $crumb = trim($crumb);
            $cat .= (!empty($cat)) ? $divider : '';
            $cat .= $crumb;
        }
        return $cat;
    }
    
    /**
     * Get description
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getDescription(SimpleXMLElement $xml): string
    {
        return trim((string)$xml->desc);
    }

    /**
     * Get images
     *
     * @param SimpleXMLElement $xml
     * @return array
     */
    public function getImages(SimpleXMLElement $xml): array
    {
        $images = [];
        foreach ($xml->imgs->children() as $img) {
            $url = (string)$img['url'];
            array_push($images, [
                'main' => !sizeof($images),
                'url' => $url,
            ]);
        }
        return $images;
    }

    /**
     * Get attributes
     *
     * @param SimpleXMLElement $xml
     * @return array
     */
    public function getAttributes(SimpleXMLElement $xml): array
    {
        $attributes = [];
        foreach ($xml->attrs->children() as $attribute){
            $name = (string) $attribute['name'];
            $value = trim((string) $attribute);
            if ($name && $value){
                array_push($attributes, [
                    'name' =>$name,
                    'value'=>$value,
                ]);
            }
        }
        return $attributes;
    }

    /**
     * Get attribute value
     *
     * @param string $key
     * @param array $attributes
     * @param bool $ignoreCase
     * @return mixed|null
     */
    public function getAttrubuteValue(string $key, array $attributes, bool $ignoreCase = false)
    {
        $key = $ignoreCase ? $key : mb_strtolower($key);
        foreach ($attributes as $attribute){
            $name = $ignoreCase ? $attribute['name'] : mb_strtolower($attribute['name']);
            if ($key === $name){
                return $attribute['value'];
            }
        }
        return null;
    }

    /**
     * Build image ID
     *
     * @param $url
     * @return string
     */
    public function buildImageId($url):string
    {
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        $query = $parsedUrl['query'] ?? '';
        $query = $query ? '?' .$query : '';
        return sprintf('%s%s',$path , $query);
    }

}

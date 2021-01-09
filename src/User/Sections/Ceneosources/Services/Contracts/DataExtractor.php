<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Services\Contracts;
use SimpleXMLElement;

interface DataExtractor
{
    /**
     * Get ID
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getId(SimpleXMLElement $xml): string;

    /**
     * Get brutto price
     *
     * @param SimpleXMLElement $xml
     * @return float
     */
    public function getBruttoPrice(SimpleXMLElement $xml): float;

    /**
     * Get stock
     *
     * @param SimpleXMLElement $xml
     * @return int
     */
    public function getStock(SimpleXMLElement $xml): int;

    /**
     * Get availability
     *
     * @param SimpleXMLElement $xml
     * @return int
     */
    public function getAvailability(SimpleXMLElement $xml): int;

    /**
     * Get name
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getName(SimpleXMLElement $xml): string;

    /**
     * Get cat
     *
     * @param SimpleXMLElement $xml
     * @param string $divider
     * @return string
     */
    public function getCat(SimpleXMLElement $xml, string $divider = '/'):string;

    /**
     * Get description
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getDescription(SimpleXMLElement $xml): string;

    /**
     * Get images
     *
     * @param SimpleXMLElement $xml
     * @return array
     */
    public function getImages(SimpleXMLElement $xml): array;

    /**
     * Get attributes
     *
     * @param SimpleXMLElement $xml
     * @return array
     */
    public function getAttributes(SimpleXMLElement $xml): array;

    /**
     * Get attribute value
     *
     * @param string $key
     * @param array $attributes
     * @param bool $ignoreCase
     * @return mixed|null
     */
    public function getAttrubuteValue(string $key, array $attributes, bool $ignoreCase = false);

    /**
     * Build image ID
     *
     * @param $url
     * @return string
     */
    public function buildImageId($url):string;
}

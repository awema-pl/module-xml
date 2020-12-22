<?php

namespace AwemaPL\Xml\Client;

use InvalidArgumentException;

class Config
{
    /** @var string $url */
    private $url;

    /** @var bool $downloadBefore */
    private $downloadBefore;

    /** @var bool $verifySsl */
    private $verifySsl;

    /** @var string $userAgent */
    private $userAgent;

    public function __construct(array $parameters)
    {
        $this->set($parameters);
    }

    /**
     * Set
     *
     * @param array $parameters
     * @return $this
     */
    public function set(array $parameters): self
    {
        if (!$parameters['url']) {
            throw new InvalidArgumentException('Parameter "url" must be provided in the configuration.');
        }
        $this->url = $parameters['url'];
        $this->downloadBefore = $parameters['download_before'] ?? true;
        $this->verifySsl = $parameters['verify_ssl'] ?? false;
        $this->userAgent = $parameters['user_agent'] ?? '';
        return $this;
    }

    /**
     * Get URL
     * 
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Is download before
     *
     * @return bool
     */
    public function isDownloadBefore(): bool
    {
        return $this->downloadBefore;
    }

    /**
     * Verify SSL
     *
     * @return bool
     */
    public function isVerifySsl(): bool
    {
        return $this->verifySsl;
    }

    /**
     * Get user agent
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}

<?php
declare(strict_types=1);

namespace App\Data;

use DateTimeInterface;

final class LogData
{
    /**
     * @param string $serviceName
     * @param \DateTimeInterface $timestamp
     * @param string $requestMethod
     * @param string $requestUri
     * @param string $requestHeader
     * @param int $statusCode
     */
    public function __construct (
        private string $serviceName,
        private DateTimeInterface $timestamp,
        private string $requestMethod,
        private string $requestUri,
        private string $requestHeader,
        private int $statusCode
    ) {}

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    public function getRequestHeader(): string
    {
        return $this->requestHeader;
    }

    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getUniqueIdentifier(): string
    {
        $data = implode('_', [
            $this->serviceName,
            serialize($this->timestamp),
            $this->requestUri,
            $this->requestHeader,
            $this->requestMethod,
            $this->statusCode
        ]);

        return md5($data);
    }
}

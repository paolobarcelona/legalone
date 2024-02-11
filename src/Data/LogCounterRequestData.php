<?php
declare(strict_types=1);

namespace App\Data;

use \DateTimeInterface;

final class LogCounterRequestData
{
    /**
     * @var iterable<string>|null
     */
    private ?iterable $serviceNames = null;

    private ?int $statusCode = null;

    private ?DateTimeInterface $startDate = null;

    private ?DateTimeInterface $endDate = null;

    /**
     * @return iterable<string>|null
     */
    public function getServiceNames(): ?iterable
    {
        return $this->serviceNames;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setServiceNames(?iterable $serviceNames = null): self
    {
        $this->serviceNames = $serviceNames;

        return $this;
    }

    public function setStatusCode(?int $statusCode = null): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setStartDate(?DateTimeInterface $startDate = null): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function setEndDate(?DateTimeInterface $endDate = null): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}

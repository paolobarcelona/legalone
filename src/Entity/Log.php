<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
#[ORM\UniqueConstraint(name:"identifier_idx", columns:["identifier"])]
class Log
{
    public const TIMESTAMP_FORMAT = 'Y-m-d H:i:sP';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $serviceName = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column(length: 255)]
    private ?string $requestUri = null;

    #[ORM\Column(length: 255)]
    private ?string $requestHeader = null;

    #[ORM\Column(length: 255)]
    private ?string $requestMethod = null;

    #[ORM\Column]
    private ?int $statusCode = null;

    #[ORM\Column(length: 32, unique: true)]
    private ?string $identifier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): static
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getRequestUri(): ?string
    {
        return $this->requestUri;
    }

    public function setRequestUri(string $requestUri): static
    {
        $this->requestUri = $requestUri;

        return $this;
    }

    public function getRequestHeader(): ?string
    {
        return $this->requestHeader;
    }

    public function setRequestHeader(string $requestHeader): static
    {
        $this->requestHeader = $requestHeader;

        return $this;
    }

    public function getRequestMethod(): ?string
    {
        return $this->requestMethod;
    }

    public function setRequestMethod(string $requestMethod): static
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }
}

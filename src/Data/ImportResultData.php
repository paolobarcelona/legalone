<?php
declare(strict_types=1);

namespace App\Data;

final class ImportResultData
{
    public function __construct(
        private ?int $saved = null,
        private ?string $failedMessage = null
    ) {}

    public function getSaved(): int
    {
        return $this->saved ?? 0;
    }

    public function getFailedMessage(): ?string
    {
        return $this->failedMessage;
    }

    public function addSaved(): void
    {
        $this->saved += 1;
    }

    public function setSaved(int $saved): void
    {
        $this->saved = $saved;
    }

    public function setFailedMessage(string $failedMessage): void
    {
        $this->failedMessage = $failedMessage;
    }
}

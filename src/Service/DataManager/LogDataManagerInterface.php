<?php
declare(strict_types=1);

namespace App\Service\DataManager;

use App\Data\LogCounterRequestData;
use App\Entity\Log;

interface LogDataManagerInterface
{
    public function countLogsByFilters(LogCounterRequestData $requestData): int;

    public function create(Log $log): void;

    public function getLastCreatedLog(): ?Log;

    public function truncate(): void;
}
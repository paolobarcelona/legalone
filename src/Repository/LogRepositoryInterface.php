<?php

namespace App\Repository;

use App\Data\LogCounterRequestData;
use App\Entity\Log;

interface LogRepositoryInterface
{
    /**
     * @return void
     */
    public function deleteAllLogs(): void;

    /**
     * @return \App\Entity\Log|null
     */
    public function getLastSavedLog(): ?Log;

    /**
     * @param \App\Data\LogCounterRequestData $requestData
     * 
     * @return int
     */
    public function countLogsByFilters(LogCounterRequestData $requestData): int; 
}

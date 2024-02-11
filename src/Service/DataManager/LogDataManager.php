<?php
declare(strict_types=1);

namespace App\Service\DataManager;

use App\Data\LogCounterRequestData;
use App\Entity\Log;
use App\Repository\LogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class LogDataManager implements LogDataManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LogRepositoryInterface $logRepository
    ) {}

    public function countLogsByFilters(LogCounterRequestData $requestData): int
    {
        return $this->logRepository->countLogsByFilters($requestData);
    }

    public function create(Log $log): void
    {
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function getLastCreatedLog(): ?Log
    {
        return $this->logRepository->getLastSavedLog();
    }

    public function truncate(): void
    {
        $this->logRepository->deleteAllLogs();
    }
}
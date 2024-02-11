<?php
declare(strict_types=1);

namespace App\Service\DataManager;

use App\Data\LogCounterRequestData;
use App\Entity\Log;
use App\Repository\LogRepositoryInterface;
use Doctrine\ORM\EntityManager;
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
        $this->checkEntityManagerHealth();

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

    private function checkEntityManagerHealth(): void
    {
        if ($this->entityManager->isOpen() === true) {
            return;
        }

        $this->entityManager = new EntityManager(
            $this->entityManager->getConnection(),
            $this->entityManager->getConfiguration()
        );        
    }
}
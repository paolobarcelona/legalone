<?php
declare(strict_types=1);

namespace App\Service\Importer\Log;

use App\Data\ImportResultData;
use App\Entity\Log;
use App\Repository\LogRepositoryInterface;
use App\Service\Parser\Log\LogParserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

final class LogImporter implements LogImporterInterface
{
    public function __construct (
        private EntityManagerInterface $entityManager,
        private LogParserInterface $logParser,
        private LogRepositoryInterface $logRepository
    ) {}

    /**
     * @inheritDoc
     */
    public function importLocalLogFile(?string $filePath = null): ImportResultData
    {
        $filePath = $filePath ?? '/app/public/logs/logs.log';

        $result = new ImportResultData();

        $saved = 0;

        $logsData = $this->logParser->parseLocalFile($filePath);
        $logsData = $this->removeAlreadySavedLines($logsData);
        
        foreach ($logsData as $logData) {
            $log = (new Log())
                ->setServiceName($logData->getServiceName())
                ->setTimestamp($logData->getTimestamp())
                ->setRequestMethod($logData->getRequestMethod())
                ->setRequestUri($logData->getRequestUri())
                ->setRequestHeader($logData->getRequestHeader())
                ->setStatusCode($logData->getStatusCode())
                ->setIdentifier($logData->getUniqueIdentifier());

            $this->entityManager->persist($log);

            $saved++;
        }

        try {
            $this->entityManager->flush();

            $result->setSaved($saved);
        } catch (Throwable $exception) {
            $result->setFailedMessage($exception->getMessage());
        }

        return $result;
    }

    /**
     * @param iterable<\App\Data\LogData> $logsData
     * 
     * @return iterable<\App\Data\LogData>
     */    
    private function removeAlreadySavedLines(iterable $logsData): iterable
    {
        $lastSavedLog = $this->logRepository->getLastSavedLog();

        if ($lastSavedLog === null) {
            return $logsData;
        }

        foreach ($logsData as $identifier => $logData) {
            // Keep unsetting until we reach the last saved row
            unset($logsData[$identifier]);

            if ($identifier === $lastSavedLog->getIdentifier()) {
                // Stop on the last saved line
                break;
            }
        }

        return $logsData;
    }
}

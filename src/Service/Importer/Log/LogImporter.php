<?php
declare(strict_types=1);

namespace App\Service\Importer\Log;

use App\Service\DataManager\LogDataManagerInterface;
use App\Service\Parser\Log\LogParserInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class LogImporter implements LogImporterInterface
{
    public function __construct (
        private LogParserInterface $logParser,
        private LogDataManagerInterface $logDataManager,
        private MessageBusInterface $messageBus
    ) {}

    /**
     * @inheritDoc
     */
    public function importLocalLogFile(?string $filePath = null): void
    {
        $filePath = $filePath ?? '/app/public/logs/logs.log';

        $logsData = $this->logParser->parseLocalFile($filePath);
        $logsData = $this->removeAlreadySavedLines($logsData);

        foreach ($logsData as $logData) {
            $this->messageBus->dispatch($logData);
        }
    }

    /**
     * @param iterable<\App\Message\LogData> $logsData
     * 
     * @return iterable<\App\Message\LogData>
     */    
    private function removeAlreadySavedLines(iterable $logsData): iterable
    {
        $lastSavedLog = $this->logDataManager->getLastCreatedLog();

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

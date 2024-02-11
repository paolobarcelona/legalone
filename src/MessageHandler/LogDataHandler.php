<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Log;
use App\Message\LogData;
use App\Service\DataManager\LogDataManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
final class LogDataHandler
{
    public function __construct(private LogDataManagerInterface $logDataManager) {}

    public function __invoke(LogData $logData)
    {
        try {
            $log = (new Log())
                ->setServiceName($logData->getServiceName())
                ->setTimestamp($logData->getTimestamp())
                ->setRequestMethod($logData->getRequestMethod())
                ->setRequestUri($logData->getRequestUri())
                ->setRequestHeader($logData->getRequestHeader())
                ->setStatusCode($logData->getStatusCode())
                ->setIdentifier($logData->getUniqueIdentifier());

            $this->logDataManager->create($log);
        } catch (Throwable $exception) {
            // Do nothing
        }        
    }
}
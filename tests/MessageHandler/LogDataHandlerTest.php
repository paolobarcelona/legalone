<?php
declare(strict_types=1);

namespace App\Tests\Service\DataManager;

use App\Entity\Log;
use App\Message\LogData;
use App\MessageHandler\LogDataHandler;
use App\Service\DataManager\LogDataManagerInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group LogDataHandlerTest
 */
final class LogDataHandlerTest extends KernelTestCase
{
    public function testInvoke(): void
    {
        $logData = new LogData(
            'USER-SERVICE', 
            new DateTime('18/Aug/2018:10:33:59 +0000'), 
            'POST', 
            '/users', 
            'HTTP/1.1', 
            201
        );

        $log = (new Log())
            ->setServiceName($logData->getServiceName())
            ->setTimestamp($logData->getTimestamp())
            ->setRequestMethod($logData->getRequestMethod())
            ->setRequestUri($logData->getRequestUri())
            ->setRequestHeader($logData->getRequestHeader())
            ->setStatusCode($logData->getStatusCode())
            ->setIdentifier($logData->getUniqueIdentifier());      

        $dataManager = $this->createMock(LogDataManagerInterface::class);
        $dataManager
            ->expects($this->once())
            ->method('create')
            ->with($log);

        $handler = new LogDataHandler($dataManager);
        $handler->__invoke($logData);
    }
}
<?php
declare(strict_types=1);

namespace App\Tests\Service\Parser;

use App\Entity\Log;
use App\Message\LogData;
use App\Service\DataManager\LogDataManagerInterface;
use App\Service\Importer\Log\LogImporter;
use App\Service\Importer\Log\LogImporterInterface;
use App\Service\Parser\Log\LogParserInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @group LogImporterTest
 */
final class LogImporterTest extends KernelTestCase
{
    private MockObject|LogParserInterface $logParser;

    private MockObject|LogDataManagerInterface $logDataManager;

    private MockObject|MessageBusInterface $messageBus;

    private LogImporterInterface $logImporter;

    protected function setUp(): void
    {
        $this->logParser = $this->createMock(LogParserInterface::class);
        $this->logDataManager = $this->createMock(LogDataManagerInterface::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->logImporter = new LogImporter(
            $this->logParser, 
            $this->logDataManager,
            $this->messageBus
        );
    }

    public function testImportLocalLogFileSuccess(): void
    {
        $logData = new LogData(
            'USER-SERVICE', 
            new DateTime('18/Aug/2018:10:33:59 +0000'), 
            'POST', 
            '/users', 
            'HTTP/1.1', 
            201
        );

        $path = 'tests/data/test.log';

        $this->logParser
            ->expects($this->once())
            ->method('parseLocalFile')
            ->with($path)
            ->willReturn([$logData]);

        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($logData)
            ->willReturn(new Envelope($logData));


        $this->logDataManager
            ->expects($this->once())
            ->method('getLastCreatedLog')
            ->willReturn(null);        

        $this->logImporter->importLocalLogFile($path);
    }

    public function testImportLocalLogFileSuccessWithPreviouslySavedLogs(): void
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

        $path = 'tests/data/test.log';

        $this->logParser
            ->expects($this->once())
            ->method('parseLocalFile')
            ->with($path)
            ->willReturn([$logData]);

        $this->messageBus
            ->expects($this->never())
            ->method('dispatch');

        $this->logDataManager
            ->expects($this->once())
            ->method('getLastCreatedLog')
            ->willReturn($log);        

        $this->logImporter->importLocalLogFile($path);
    }
}

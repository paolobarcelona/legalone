<?php
declare(strict_types=1);

namespace App\Tests\Service\Parser;

use App\Data\LogData;
use App\Entity\Log;
use App\Repository\LogRepositoryInterface;
use App\Service\Importer\Log\LogImporter;
use App\Service\Parser\Log\LogParserInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LogImporterTest extends KernelTestCase
{
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

        $log = (new Log())
            ->setServiceName($logData->getServiceName())
            ->setTimestamp($logData->getTimestamp())
            ->setRequestMethod($logData->getRequestMethod())
            ->setRequestUri($logData->getRequestUri())
            ->setRequestHeader($logData->getRequestHeader())
            ->setStatusCode($logData->getStatusCode())
            ->setIdentifier($logData->getUniqueIdentifier());

        $path = 'tests/data/test.log';

        $parser = $this->createMock(LogParserInterface::class);
        $parser
            ->expects($this->once())
            ->method('parseLocalFile')
            ->with($path)
            ->willReturn([$logData]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($log);

        $entityManager
            ->expects($this->once())
            ->method('flush');

        $logRepository = $this->createMock(LogRepositoryInterface::class);
        $logRepository
            ->expects($this->once())
            ->method('getLastSavedLog')
            ->willReturn(null);        

        $importer = new LogImporter(
            $entityManager,
            $parser,
            $logRepository
        );

        $result = $importer->importLocalLogFile($path);

        self::assertEquals(1, $result->getSaved());
        self::assertNull($result->getFailedMessage());
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

        $parser = $this->createMock(LogParserInterface::class);
        $parser
            ->expects($this->once())
            ->method('parseLocalFile')
            ->with($path)
            ->willReturn([$logData]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->never())
            ->method('persist')
            ->with($log);

        $entityManager
            ->expects($this->once())
            ->method('flush');

        $logRepository = $this->createMock(LogRepositoryInterface::class);
        $logRepository
            ->expects($this->once())
            ->method('getLastSavedLog')
            ->willReturn($log);        

        $importer = new LogImporter(
            $entityManager,
            $parser,
            $logRepository
        );

        $result = $importer->importLocalLogFile($path);

        self::assertEquals(0, $result->getSaved());
        self::assertNull($result->getFailedMessage());
    }

    public function testImportLocalLogFileSuccessWithErrors(): void
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

        $exception = new Exception('Something went wrong.');

        $parser = $this->createMock(LogParserInterface::class);
        $parser
            ->expects($this->once())
            ->method('parseLocalFile')
            ->with($path)
            ->willReturn([$logData]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->never())
            ->method('persist')
            ->with($log);

        $entityManager
            ->expects($this->once())
            ->method('flush')
            ->willThrowException($exception);

        $logRepository = $this->createMock(LogRepositoryInterface::class);
        $logRepository
            ->expects($this->once())
            ->method('getLastSavedLog')
            ->willReturn($log);        

        $importer = new LogImporter(
            $entityManager,
            $parser,
            $logRepository
        );

        $result = $importer->importLocalLogFile($path);

        self::assertEquals(0, $result->getSaved());
        self::assertNotNull($result->getFailedMessage());
        self::assertEquals($exception->getMessage(), $result->getFailedMessage()); 
    }
}

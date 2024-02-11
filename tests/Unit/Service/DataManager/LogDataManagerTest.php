<?php
declare(strict_types=1);

namespace App\Tests\Service\DataManager;

use App\Data\LogCounterRequestData;
use App\Entity\Log;
use App\Repository\LogRepositoryInterface;
use App\Service\DataManager\LogDataManager;
use App\Service\DataManager\LogDataManagerInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group LogDataManagerTest
 */
final class LogDataManagerTest extends KernelTestCase
{
    private MockObject|EntityManagerInterface $entityManager;

    private MockObject|LogRepositoryInterface $logRepository;

    private LogDataManagerInterface $dataManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logRepository = $this->createMock(LogRepositoryInterface::class);
        $this->dataManager = new LogDataManager($this->entityManager, $this->logRepository);
    }

    public function testCountLogsByFilters(): void
    {
        $requestData = new LogCounterRequestData();
        $requestData->setStatusCode(201);
        
        $this->logRepository
            ->expects($this->once())
            ->method('countLogsByFilters')
            ->with($requestData)
            ->willReturn(1);

        $this->dataManager->countLogsByFilters($requestData);
    }

    public function testCreate(): void
    {
        $log = new Log();
        $log
            ->setServiceName('random service')
            ->setTimestamp(new DateTime('now'))
            ->setRequestMethod('PUT')
            ->setRequestUri('/')
            ->setRequestHeader('HTTP/1.1')
            ->setStatusCode(201)
            ->setIdentifier(md5('random-identifier'));

        $this->entityManager
            ->expects($this->once())
            ->method('isOpen')
            ->willReturn(true);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($log);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->dataManager->create($log);
    }

    public function testGetLastCreatedLog(): void
    {
        $log = new Log();
        $log
            ->setServiceName('random service')
            ->setTimestamp(new DateTime('now'))
            ->setRequestMethod('PUT')
            ->setRequestUri('/')
            ->setRequestHeader('HTTP/1.1')
            ->setStatusCode(201)
            ->setIdentifier(md5('random-identifier'));

        $this->logRepository
            ->expects($this->once())
            ->method('getLastSavedLog')
            ->willReturn($log);

        $lastCreated = $this->dataManager->getLastCreatedLog();

        self::assertEquals($log->getIdentifier(), $lastCreated->getIdentifier());
    }

    public function testTruncate(): void
    {
        $this->logRepository
            ->expects($this->once())
            ->method('deleteAllLogs');

        $this->dataManager->truncate();      
    }
}
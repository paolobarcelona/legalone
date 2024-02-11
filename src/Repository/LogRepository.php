<?php
declare(strict_types=1);

namespace App\Repository;

use App\Data\LogCounterRequestData;
use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 *
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class LogRepository extends ServiceEntityRepository implements LogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @inheritDoc
     */
    public function deleteAllLogs(): void
    {
        $this
            ->createQueryBuilder('l')
            ->delete()
            ->getQuery()
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function getLastSavedLog(): ?Log
    {
        return $this
            ->createQueryBuilder('l')
            ->orderBy('l.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */     
    public function countLogsByFilters(LogCounterRequestData $requestData): int
    {
        $queryBuilder = $this->createQueryBuilder('l');
        $queryBuilder->select('COUNT(l.id) AS totalLogs');

        if (count($requestData->getServiceNames() ?? []) > 0) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('l.serviceName', ':serviceNames'))
                ->setParameter('serviceNames', $requestData->getServiceNames());
        }

        if ($requestData->getStatusCode() !== null) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('l.statusCode', ':statusCode'))
                ->setParameter('statusCode', $requestData->getStatusCode());
        }


        if ($requestData->getStartDate() !== null) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte('l.timestamp', ':startDate'))
                ->setParameter('startDate', (string)$requestData->getStartDate()->format(Log::TIMESTAMP_FORMAT));
        }

        if ($requestData->getEndDate() !== null) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lte('l.timestamp', ':endDate'))
                ->setParameter('endDate', (string)$requestData->getEndDate()->format(Log::TIMESTAMP_FORMAT));
        }

        $result = $queryBuilder->getQuery()->setMaxResults(1)->getOneOrNullResult();

        return $result['totalLogs'] ?? 0;
    }
}

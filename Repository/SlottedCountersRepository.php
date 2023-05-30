<?php

/**
 * Implementation of the slotted counters pattern as described by Sam Lambert - https://planetscale.com/blog/the-slotted-counter-pattern
 * for Doctrine / Symfony by Paul Hempshall - https://www.paulhempshall.com
 */

namespace App\Repository;

use App\Entity\SlottedCounters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SlottedCounters>
 *
 * @method SlottedCounters|null find($id, $lockMode = null, $lockVersion = null)
 * @method SlottedCounters|null findOneBy(array $criteria, array $orderBy = null)
 * @method SlottedCounters[]    findAll()
 * @method SlottedCounters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlottedCountersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SlottedCounters::class);
    }

    public function save(SlottedCounters $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SlottedCounters $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function incrementByTypeAndId(int $record_type, int $record_id): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            INSERT INTO slotted_counters(record_type, record_id, slot, count)
            VALUES (:record_type, :record_id, RAND() * 100, 1)
            ON DUPLICATE KEY UPDATE count = count + 1;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['record_type' => $record_type, 'record_id' => $record_id]);
    }

    public function findByTypeAndId(int $record_type, int $record_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SUM(count) as count FROM slotted_counters
            WHERE (record_type = :record_type AND record_id = :record_id);
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['record_type' => $record_type, 'record_id' => $record_id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /** Example **/
    // public function findSuccessById(int $record_id): int
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //         SELECT SUM(count) as count FROM slotted_counters
    //         WHERE (record_type = 200 AND record_id = :record_id);
    //     ';

    //     $stmt = $conn->prepare($sql);
    //     $resultSet = $stmt->executeQuery(['record_id' => $record_id]);

    //     return intval($resultSet->fetchAllAssociative()[0]['count']);
    // }
}

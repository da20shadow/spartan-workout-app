<?php

namespace App\Repository;

use App\Entity\Exercises;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercises>
 *
 * @method Exercises|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercises|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercises[]    findAll()
 * @method Exercises[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExercisesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercises::class);
    }

    public function save(Exercises $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Exercises $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return array Returns an array of Exercises objects
     */
    public function findFromDate($fromDate,int $user_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM exercises e
            WHERE e.workout_time >= :from_date AND e.user_id = :user_id
            ORDER BY e.workout_time ASC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['from_date' => $fromDate,'user_id' => $user_id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findTodayWorkouts()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT * FROM exercises
            WHERE DAY(workout_time) =  DAY(NOW()) 
            AND MONTH(workout_time) = MONTH(NOW()) 
            AND YEAR(workout_time) = Year(NOW())
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        if ($resultSet){
            return $resultSet->fetchAssociative();
        }
        return [];
    }
//    /**
//     * @return Exercises[] Returns an array of Exercises objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Exercises
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

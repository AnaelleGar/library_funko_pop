<?php

namespace App\Repository;

use App\Entity\TotalMovie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TotalMovie>
 *
 * @method TotalMovie|null find($id, $lockMode = null, $lockVersion = null)
 * @method TotalMovie|null findOneBy(array $criteria, array $orderBy = null)
 * @method TotalMovie[]    findAll()
 * @method TotalMovie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TotalMovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TotalMovie::class);
    }

    /**
     * @param TotalMovie $entity
     * @param bool     $flush
     *
     * @return void
     */
    public function add(TotalMovie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TotalMovie[] Returns an array of TotalMovie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TotalMovie
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

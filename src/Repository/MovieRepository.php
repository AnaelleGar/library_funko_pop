<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @param Movie $entity
     * @param bool  $flush
     *
     * @return void
     */
    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Movie $entity
     * @param bool  $flush
     *
     * @return void
     */
    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return QueryBuilder
     */
    public function getAllQb(): QueryBuilder
    {
        return $this->createQueryBuilder('m');
    }

    /**
     * @param Category|null $category
     * @param bool $getQueryBuilder
     *
     * @return QueryBuilder
     */
    public function findAllInCategory(?Category $category, bool $getQueryBuilder = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->leftJoin('m.category', 'c');

        if (null !== $category) {
            $queryBuilder->andWhere('m.category = :category')
                ->setParameter('category', $category);
        }

        if (true === $getQueryBuilder) {
            return $queryBuilder;
        }

        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

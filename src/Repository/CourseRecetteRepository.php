<?php

namespace App\Repository;

use App\Entity\CourseRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseRecette>
 *
 * @method CourseRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseRecette[]    findAll()
 * @method CourseRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseRecette::class);
    }

    public function add(CourseRecette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CourseRecette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findRecettes($value): array
    // {
    //     return $this->createQueryBuilder('c')
    //        ->andWhere('c.course = :val')
    //        ->setParameter('val', $value)
    //        ->getQuery()
    //        ->getResult()
    //    ;
    // }

//    /**
//     * @return CourseRecette[] Returns an array of CourseRecette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CourseRecette
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

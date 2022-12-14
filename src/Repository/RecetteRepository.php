<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function add(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
 * Repository method for finding the newest inserted
 * entry inside the database. Will return the latest
 * entry when one is existent, otherwise will return
 * null.
 *
 * @return MyTable|null
 */
public function findLastInserted()
{
    return $this
        ->createQueryBuilder("e")
        ->orderBy("id", "DESC")
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}

   /**
    * @return Recette[] Returns an array of Recette objects
    */
   public function findAllSorted(): array
   {
       return $this->createQueryBuilder('r')
           ->orderBy('r.name', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function search($filtres){

        $query = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');
            // ->leftJoin('p.source', 'source');
            // ->leftJoin('p.ingredient', 'ingredient');

        if(!empty($filtres) && !is_null($filtres["search"])){
            $query->where('p.name LIKE :name')
                ->orWhere('p.description LIKE :name')
                // ->orWhere('source LIKE :name')
                // ->orWhere('ingredient LIKE :name')
                ->setParameter('name', '%'.$filtres["search"].'%');
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Recette[] Returns an array of Recette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recette
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function add(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Annonce[] Returns an array of Annonce objects
    */
   public function findByUser($user): array
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.auteur = :val')
           ->setParameter('val', $user)
           ->orderBy('a.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

      /**
    * @return Annonce[] Returns an array of Annonce objects
    */
    public function findAllValide(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.valide = :val2')
            ->setParameter('val2', '1')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

  

//    public function findOneBySomeField($value): ?Annonce
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

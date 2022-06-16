<?php

namespace App\Repository;

use App\Entity\Candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidature>
 *
 * @method Candidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidature[]    findAll()
 * @method Candidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

    public function add(Candidature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Candidature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Candidature[] Returns an array of Candidature objects
    */
   public function findByUser($user): array
   {
       return $this->createQueryBuilder('candidatures')
           ->andWhere('candidatures.candidat_id = :val')
           ->setParameter('val', $user)
           ->getQuery()
           ->getResult()
       ;
   }

      /**
    * @return Candidature[] Returns an array of Candidature objects
    */
    public function findValideByAnnonce($annonce): array
    {
        return $this->createQueryBuilder('candidatures')
            ->andWhere('candidatures.annonce_id = :val')
            ->andWhere('candidatures.valide = :val2')
            ->setParameter('val', $annonce)
            ->setParameter('val2', '1')
            ->getQuery()
            ->getResult()
        ;
    }


   public function findOneByUserAndAnnonce($user, $annonce): ?Candidature
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.candidat_id = :val')
           ->andWhere('c.annonce_id = :val2')
           ->setParameter('val', $user)
           ->setParameter('val2', $annonce)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}

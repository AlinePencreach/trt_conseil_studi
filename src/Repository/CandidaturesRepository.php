<?php

namespace App\Repository;

use App\Entity\Candidatures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidatures>
 *
 * @method Candidatures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidatures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidatures[]    findAll()
 * @method Candidatures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidaturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidatures::class);
    }

    public function add(Candidatures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Candidatures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

       /**
    * @return Candidatures[] Returns an array of Candidature objects
    */
    public function findByAnnonce($annonce): array
    {
        return $this->createQueryBuilder('candidatures')
            ->andWhere('candidatures.annonce_id = :val')
            ->setParameter('val', $annonce)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Candidatures[] Returns an array of Candidatures objects
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

//    public function findOneBySomeField($value): ?Candidatures
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

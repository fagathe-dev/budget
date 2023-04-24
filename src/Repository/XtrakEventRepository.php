<?php

namespace App\Repository;

use App\Entity\XtrakEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<XtrakEvent>
 *
 * @method XtrakEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method XtrakEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method XtrakEvent[]    findAll()
 * @method XtrakEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XtrakEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XtrakEvent::class);
    }

    public function save(XtrakEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(XtrakEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return XtrakEvent[] Returns an array of XtrakEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('x.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?XtrakEvent
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

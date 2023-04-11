<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Expense;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Expense>
 *
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private Security $security
    ) {
        parent::__construct($registry, Expense::class);
    }

    public function save(Expense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Expense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Expense[] Returns an array of Expense objects
    */
   public function findUserLatestExpenses(): array
   {
        $user = $this->security->getUser();
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :userId')
            ->andWhere('e.isPaid = :isPaid')
            ->setParameter('isPaid', false)
            ->setParameter('userId',  $user instanceof User ? $user->getId() : null)
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
   }

//    public function findOneBySomeField($value): ?Expense
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

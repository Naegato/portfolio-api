<?php

namespace App\Repository;

use App\Entity\Techno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Techno>
 *
 * @method Techno|null find($id, $lockMode = null, $lockVersion = null)
 * @method Techno|null findOneBy(array $criteria, array $orderBy = null)
 * @method Techno[]    findAll()
 * @method Techno[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Techno::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Techno $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Techno $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Techno[] Returns an array of Techno objects
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

    public function findById(int $id): ?Techno {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    public function findOneBySomeField($value): ?Techno
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

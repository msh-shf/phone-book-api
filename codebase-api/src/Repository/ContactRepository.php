<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function add(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByName(string $name, int $page = 1, int $size = 20): Pagination
    {
        $offset = ($page - 1) * $size;

        $query = $this->createQueryBuilder("c")
            ->andWhere("CONCAT(c.first_name, ' ', c.last_name) LIKE :name")
            ->setParameter('name', "%" . $name . "%")
            ->orderBy('c.created_at', 'DESC')
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $count = count($paginator);
        $pageContent = new ArrayCollection();

        /** @var Contact $contact */
        foreach ($paginator as $contact) {
            $pageContent->add($contact->asArray());
        }

        return Pagination::create($pageContent, $count, $page, $size);
    }
}

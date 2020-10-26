<?php
/**
 * Created by IntelliJ IDEA.
 * User: jdavid
 * Date: 08/03/2019
 * Time: 14:46
 */

namespace App\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class KnpPaginatorRepository
 * @package App\Repository
 *
 */
abstract class KnpPaginatorRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * AbstractRepository constructor.
     *
     * @param PaginatorInterface $paginator
     * @param ManagerRegistry $registry
     * @param string $entityClass
     *
     */
    public function __construct(PaginatorInterface $paginator, ManagerRegistry $registry, $entityClass)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param int $pageNumber
     * @param int $maxPerPage
     * @param array options
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     *
     */
    public function paginate(QueryBuilder $queryBuilder, $pageNumber, $maxPerPage, $options = array())
    {
        return $this->paginator->paginate(
                $queryBuilder,
                $pageNumber,
                $maxPerPage,
                $options
            );
    }

}
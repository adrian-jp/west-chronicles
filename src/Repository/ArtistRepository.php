<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Model\SearchArtistModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\KnpPaginatorRepository;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends KnpPaginatorRepository
{
    /**
     * ArtistRepository constructor.
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($paginator, $registry,Artist::class);
    }

    /**
     * Récupère tous les albums d'un album
     * @param $id
     * @return int|mixed|string
     */
    public function findAllAlbumsByArtist($id)
    {
        return $this->createQueryBuilder('artist')
            ->addSelect('albums')
            ->addSelect('tracks')
            ->leftJoin('artist.albums', 'albums')
            ->leftJoin('albums.tracks', 'tracks', 'tracks.album = :albums')
            ->andWhere('artist.id = :val')
            ->setParameter('val', $id)
            ->orderBy('albums.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $pageNumber
     * @param int $maxPerPage
     * @param SearchArtistModel|null $artistModel
     */
    public function findAllArtistsPaginate($pageNumber=1, $maxPerPage=Artist::MAX_PER_PAGE, SearchArtistModel $artistModel=null) {
        $query = $this->createQueryBuilder('artist');

        $expr = $query->expr();

        $query->select('artist')
            ->addSelect('albums')
            ->addSelect('clips')
            ->leftJoin('artist.albums', 'albums')
            ->leftJoin('artist.clips', 'clips')
            ->orderBy('artist.nom', 'ASC')
        ;

        if ($artistModel != null) {

            if ($artistModel->getNom() != null) {
                $query->andWhere(
                    $expr->orX(
                        $expr->like($expr->upper($expr->concat($expr->concat('artist.nom', $expr->literal(' ')), 'artist.nom')), $expr->literal("%{$artistModel->getNom()}%"))
                    )
                );
            }

            if ($artistModel->getAlbum() != null) {
                $query->andWhere('albums = :album')
                    ->setParameter('album', $artistModel->getAlbum());
            }

            if ($artistModel->getClip() != null) {
                $query->andWhere('clips = :clip')
                    ->setParameter('clip', $artistModel->getClip());
            }

            if ($artistModel->getDepartement() != null) {
                $query->andWhere(
                    $expr->orX(
                        $expr->like($expr->upper($expr->concat($expr->concat('artist.departement', $expr->literal(' ')), 'artist.departement')), $expr->literal("%{$artistModel->getDepartement()}%"))
                    )
                );
            }
        }

        return $this->paginate($query, $pageNumber, $maxPerPage, array('wrap-queries'=>true));

    }

    // /**
    //  * @return Artist[] Returns an array of Artist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artist
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

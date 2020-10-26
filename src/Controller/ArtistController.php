<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistFormType;
use App\Model\TypeFile;
use App\Repository\AlbumRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArtistRepository;
use App\Service\ArtistService;
use App\Service\FileUploaderService;
use App\Util\Utility;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    /**
     * @var ArtistRepository
     */
    private $artistRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var AlbumRepository
     */
    private $albumRepository;

    /**
     * ArtistController constructor.
     * @param ArtistRepository $artistRepository
     * @param SerializerInterface $serializer
     * @param AlbumRepository $albumRepository
     */
    public function __construct(ArtistRepository $artistRepository, SerializerInterface $serializer, AlbumRepository $albumRepository)
    {
        $this->artistRepository = $artistRepository;
        $this->serializer = $serializer;
        $this->albumRepository = $albumRepository;
    }


    /**
     * @Route("/artist", name="artist", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $pageNumber = $request->get('page')?? 1;
        $artists = $this->artistRepository->findAllArtistsPaginate($pageNumber, Artist::MAX_PER_PAGE);
        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @Route("/artist/album/one/{id}", name="artiste_one_id", options={"expose" = true}, requirements={"id":"\d+"})
     * @param Artist $artist
     * @return Response
     */
    public function oneArtist(Artist $artist) {
        $oneArtist = $this->artistRepository->find($artist->getId());
        $listAlbums = [];
        foreach ($oneArtist->getAlbums() as $album) {

            $trackByAlbum = $this->albumRepository->findAllTracksOfAlbum($album->getId());
            for ($i=0; $i<count($trackByAlbum);$i++) {
                $albumOne = $trackByAlbum[$i]->getTracks();
                array_push($listAlbums, $albumOne);
            }
        }
        return $this->render('artist/one.html.twig', [
            'artist' => $oneArtist
        ]);
    }

    /**
     * @Route("/artist/album/one/albums/{id}", name="artiste_one_albums_id", options={"expose" = true}, requirements={"id":"\d+"})
     * @param Artist $artist
     * @return Response
     */
    public function albumsOfoneArtist(Artist $artist) {
        $oneArtist = $this->artistRepository->findAllAlbumsByArtist($artist->getId());
        $artisteOne = $this->artistRepository->find($artist->getId());
        return $this->render('artist/albums.html.twig', [
            'oneArtist' => $oneArtist,
            'artist' => $artisteOne
        ]);
    }

    /**
     * @Route("/artist/one/{id}", name="one_artist_id", options={"expose" = true}, requirements={"id":"\d+"})
     * @param Artist $artist
     * @return Response
     */
    public function artistJsonList(Artist $artist) {
        $artist = $this->artistRepository->find($artist->getId());
        $serializerContext = new SerializationContext();
        $serializerContext->setGroups(['show_album']);
        $artistsJson = $this->serializer->serialize($artist, 'json', $serializerContext);
        return new Response($artistsJson);
    }

    /**
     * @Route("/artist/creation", name="creation_artist")
     * @Route("/artist//modifier/{id}", name="modifier_artist", requirements={"id":"\d+"})
     * @param Artist|null $artist
     * @param Request $request
     * @param ArtistService $artistService
     * @param FileUploaderService $fileUploaderService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function ajouterArtist(Artist $artist=null, Request $request, ArtistService $artistService, FileUploaderService $fileUploaderService) {

        $ajout = false;
        $lastImage = null;

        if (!is_null($artist) && !is_null($artist->getImage())) {
            $lastImage = $artist->getImage();
        }

        if (is_null($artist)) {
            $artist = new Artist();
            $ajout = true;
        }

        $artistForm = $this->createForm(ArtistFormType::class, $artist);
        $artistForm->handleRequest($request);
        if ($artistForm->isSubmitted() && $artistForm->isValid()) {
            $imageFile = $artistForm->get('image')->getData();
            $nomArtiste = Utility::generateLongToCourt($artistForm->get('nom')->getData());
            if ($imageFile) {
                $extension = $imageFile->guessExtension();
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $nomArtiste. '.' . $extension;
                $imageFile->move(
                    $this->getParameter('upload_directory_artiste_photo'),
                    $newFilename
                );
                $artist->setImage($newFilename);
            } elseif (is_null($artistForm->get('image')->getData())) {
                $artist->setImage($lastImage);
            }

            $artistService->addOrEdit($artist);
            $this->redirectToRoute('artist');
        }

        return $this->render('artist/new-artist.html.twig', [
            'artist' => $artist,
            'ajout' => $ajout,
            'artistForm' => $artistForm->createView()

        ]);
    }

    /**
     * @Route("delete_photo/{id}", name="delete_photo", options={"expose"=true})
     * @param Artist $artist
     * @param EntityManagerInterface $manager
     */
    public function deletePhoto(Artist $artist, EntityManagerInterface $manager) {
        $artist->setImage('avatar.png');
        $manager->persist($artist);
        $manager->flush();

        return new Response($artist->getId());
    }
}

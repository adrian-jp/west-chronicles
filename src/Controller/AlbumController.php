<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Track;
use App\Form\AlbumFormType;
use App\Form\TrackFormType;
use App\Model\TypeFile;
use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use App\Service\AlbumService;
use App\Service\FileUploader;
use App\Service\FileUploaderService;
use App\Service\TrackService;
use App\Util\Utility;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /**
     * @var TrackRepository
     */
    private $trackRepository;
    /**
     * @var AlbumRepository
     */
    private $albumRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * AlbumController constructor.
     * @param TrackRepository $trackRepository
     * @param AlbumRepository $albumRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(TrackRepository $trackRepository, AlbumRepository $albumRepository, SerializerInterface $serializer)
    {
        $this->trackRepository = $trackRepository;
        $this->albumRepository = $albumRepository;
        $this->serializer = $serializer;
    }


    /**
     * @Route("/album", name="album")
     */
    public function index()
    {
        $albums = $this->albumRepository->findAll();
        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/album/one/detail/{id}", name="one_album", requirements={"id":"\d+"}, options={"expose" = true})
     * @param Album $album
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allTrackInAlbum(Album $album)
    {
        $album = $this->albumRepository->findAllTracksOfAlbum($album->getId());
        //dd($album);
        return $this->render('album/one.html.twig', [
            'album' => $album[0],
        ]);
    }

    /**
     * @Route("/album/one/{id}", name="one_album_json", requirements={"id":"\d+"}, options={"expose" = true})
     * @param Album $album
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allTrackInAlbumJson(Album $album)
    {
        $album = $this->albumRepository->find($album->getId());
        $albumJson = $this->serializer->serialize($album, 'json');
        return new Response($albumJson);
    }

    /**
     * @Route("/album/new", name="new_album", options={"expose" = true})
     * @Route("/album/modifier/{id}", name="modifier_album", requirements={"id":"\d+"})
     * @param Album|null $album
     * @param Track|null $track
     * @param Request $request
     * @param AlbumService $albumService
     * @param TrackService $trackService
     * @param FileUploaderService $fileUploaderService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function ajouterOuModifierAlbum(Album $album = null, Track $track = null, Request $request, AlbumService $albumService, TrackService $trackService, FileUploaderService $fileUploaderService)
    {
        $ajout = false;
        $lastImage = null;

        if (!is_null($album) && !is_null($album->getImage())) {
            $lastImage = $album->getImage();
        }

        if (is_null($album)) {
            $album = new Album();
            $ajout = true;
        }


        $albumForm = $this->createForm(AlbumFormType::class, $album);
        $albumForm->handleRequest($request);

        if ($albumForm->isSubmitted() && $albumForm->isValid()) {

            $albumTitleInput = $albumForm->get('nom')->getData();
            $albumList = $this->albumRepository->findAll();
            $exist = false;
            foreach ($albumList as $albumTitle) {
                if ($albumTitleInput === $albumTitle->getNom()) {
                    $exist = true;
                }
            }
            $imageFile = $albumForm->get('image')->getData();
            $nomAlbumCourt = Utility::generateLongToCourt($albumTitleInput);
            if ($imageFile) {
                $extension = $imageFile->guessExtension();
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $nomAlbumCourt . '.' . $extension;
                $imageFile->move(
                    $this->getParameter('upload_directory_album_photo'),
                    $newFilename
                );
                $album->setImage($newFilename);
            } elseif (is_null($albumForm->get('image')->getData())) {
                $album->setImage($lastImage);
            }

            //variable numero d'ordre dans l'album
            $ordre = 1;
            for ($i = 0, $size = count($albumForm->get('tracks')->getData()); $i < $size; $i++) {
                $track = new Track();
                $trackFile = $albumForm->get('tracks')->getData()[$i]->getUrl();
                if ($trackFile) {
                    $extension = $trackFile->guessExtension();
                }
                if ($trackFile && $trackFile->guessExtension() == 'mpga') {

                    $extension = 'mp3';
                }
                //dd($trackFile);
                if (!is_null($track) && !is_null($trackFile) && $trackFile) {
                    $exist = false;
                    $titleCourt = Utility::generateLongToCourt($albumForm->get('tracks')->getData()[$i]->getTitre());
                    try {
                        $filename = $fileUploaderService->upload($trackFile,
                            TypeFile::TYPE_FILE_TRACK,
                            $titleCourt . "." . $extension);
                        $track->setUrl($filename);
                        if ($extension == 'mp3') {
                            $track->setLength($trackService->getDuration($filename));
                        }
                    } catch (\Exception $e) {
                    }
                }
                //dd($albumForm->get('tracks')->getData()[$i]);
                if (!$ajout) {
                    $track->setOrdreAlbum(count($album->getTracks())+1);
                } else {
                    $track->setOrdreAlbum($ordre);
                }

                $track->setTitre($albumForm->get('tracks')->getData()[$i]->getTitre());
                $track->addArtist($albumForm->get('artists')->getData()[0]);
                $track->setGenre($albumForm->get('tracks')->getData()[$i]->getGenre());
                $track->setAlbum($albumForm->getData());



                $trackService->addOrEditInAlbum($track);
                //dd($track);


                $album->addTrack($track);
                $ordre = $ordre + 1;

            }
            if (!$exist) {
                //dd($album);
                $albumService->addOrEdit($album);
                $this->redirectToRoute('artist');
            } else {
                $this->addFlash('warning', 'Cet album existe déjà sur le site');
            }

        }

        return $this->render('album/new.html.twig', [
            'albumForm' => $albumForm->createView(),
            'ajout' => $ajout,
            'album' => $album
        ]);
    }

    /**
     * @Route("/album/{id}/delete_track/{track_id}", name="delete_track", options={"expose"=true})
     * @ParamConverter("id", class="App\Entity\Album", options={"id": "id"})
     * @ParamConverter("track_id", class="App\Entity\Track", options={"id": "track_id"})
     * @param Track $track
     * @param Album $album
     * @param AlbumService $albumService
     * @return Response
     */
    public function deleteTrackInAlbum(Track $track, Album $album, AlbumService $albumService)
    {
        $album->removeTrack($track);
        $albumService->addOrEdit($album);

        return $this->redirectToRoute('modifier_album', ['id' => $album->getId()]);
    }
}

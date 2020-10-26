<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Track;
use App\Form\TrackFormType;
use App\Model\TypeFile;
use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use App\Service\FileUploaderService;
use App\Service\TrackService;
use App\Util\Utility;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackController extends AbstractController
{
    /**
     * @var TrackRepository
     */
    private $trackRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var TrackService
     */
    private $trackService;
    /**
     * @var AlbumRepository
     */
    private $albumRepository;

    /**
     * TrackController constructor.
     * @param TrackRepository $trackRepository
     * @param SerializerInterface $serializer
     * @param TrackService $trackService
     * @param AlbumRepository $albumRepository
     */
    public function __construct(TrackRepository $trackRepository, SerializerInterface $serializer, TrackService $trackService, AlbumRepository $albumRepository)
    {
        $this->trackRepository = $trackRepository;
        $this->serializer = $serializer;
        $this->trackService = $trackService;
        $this->albumRepository = $albumRepository;
    }


    /**
     * @Route("/track", name="track", options={"expose" = true})
     */
    public function index()
    {
        $tracks = $this->trackRepository->findAll();
        return $this->render('track/index.html.twig', [
            'tracks' => $tracks,
        ]);
    }

    /**
     * @Route("/track-list", name="track_list", options={"expose" = true})
     * @return Response
     */
    public function trackList() {
        $tracks = $this->trackRepository->findAll();
        $jsonTracks = $this->serializer->serialize($tracks, 'json');
        return new Response($jsonTracks);
    }

    /**
     * @Route("/track/new", name="new_track", options={"expose" = true})
     * @Route("/track/modifier/{id}", name="modifier_track", requirements={"id":"\d+"})
     * @param Track|null $track
     * @param Request $request
     * @param FileUploaderService $fileUploaderService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function ajoutOuModifTrack(Track $track=null, Request $request, FileUploaderService $fileUploaderService) {
        $ajout = false;
        $albums = $this->albumRepository->findAll();

        if (is_null($track)) {
            $track = new Track();
            $ajout = true;
        }
        $formTrack = $this->createForm(TrackFormType::class, $track);
        $formTrack->handleRequest($request);

        if ($formTrack->isSubmitted() && $formTrack->isValid()) {

            $trackFile = $formTrack->get('url')->getData();
            //dd($trackFile);
            $extension = $trackFile->guessExtension();
            if ($trackFile->guessExtension() == 'mpga') {
                $extension = 'mp3';
            }
            if ($trackFile) {
                $titleCourt = Utility::generateLongToCourt($formTrack->get('titre')->getData());
                try {
                    $filename = $fileUploaderService->upload($trackFile,
                        TypeFile::TYPE_FILE_TRACK,
                        $titleCourt . "." . $extension);
                    $track->setUrl($filename);
                    if ($extension == 'mp3') {
                        $track->setLength($this->trackService->getDuration($filename));
                    }
                } catch (\Exception $e) {
                }
                foreach ($albums as $album) {
                    if ($formTrack->get('album')->getData()->getNom() == $album->getNom()) {
                        $cetAlbum = $this->albumRepository->findAllTracksOfAlbum($formTrack->get('album')->getData()->getId());
                        for ($i = 0; $i < count($cetAlbum[0]->getTracks()); $i++) {
                            $lastTrack = $cetAlbum[0]->getTracks()[count($cetAlbum[0]->getTracks())-1];
                            $track->setOrdreAlbum($lastTrack->getOrdreAlbum()+1);
                        }

                    }
                }

                $this->trackService->addOrEdit($track, $ajout);

                $this->addFlash('success', 'Track ajouté');
                return $this->redirectToRoute('track');
            } else {
                $this->addFlash('danger', 'Ce track existe déjà');
            }
        }
        return $this->render('track/new.html.twig', [
            'ajout' => $ajout,
            'formTrack' => $formTrack->createView()
        ]);
    }
}

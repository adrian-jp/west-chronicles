<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Form\SearchArtistFormType;
use App\Model\SearchArtistModel;
use App\Repository\ArticleRepository;
use App\Repository\ArtistRepository;
use App\Repository\ClipRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var ClipRepository
     */
    private $clipRepository;
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var ArtistRepository
     */
    private $artistRepository;

    /**
     * MainController constructor.
     * @param ArticleRepository $articleRepository
     * @param ClipRepository $clipRepository
     * @param EventRepository $eventRepository
     * @param ArtistRepository $artistRepository
     */
    public function __construct(ArticleRepository $articleRepository, ClipRepository $clipRepository, EventRepository $eventRepository, ArtistRepository $artistRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->clipRepository = $clipRepository;
        $this->eventRepository = $eventRepository;
        $this->artistRepository = $artistRepository;
    }


    /**
     * @Route("/", name="")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/home", name="home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accueil(Request $request)
    {
        $pageNumber = $request->get('page')?? 1;

        $searchArtist = new SearchArtistModel();
        $formSearchArtist = $this->createForm(SearchArtistFormType::class, $searchArtist);
        $formSearchArtist->handleRequest($request);

        if ($formSearchArtist->isSubmitted() && $formSearchArtist->isValid()) {
            $artists = $this->artistRepository->findAllArtistsPaginate($pageNumber, Artist::MAX_PER_PAGE, $searchArtist);
            return $this->render('artist/index.html.twig', [
                'artists' => $artists
            ]);
        }

        $articles = $this->articleRepository->findLastArticleByDate();
        $events = $this->eventRepository->findAll();
        return $this->render('main/home.html.twig', [
            'articles' => $articles,
            'events' => $events,
            'formSearchArtist' => $formSearchArtist->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreFormType;
use App\Repository\GenreRepository;
use App\Service\GenreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @var GenreRepository
     */
    private $genreRepository;

    /**
     * GenreController constructor.
     * @param GenreRepository $genreRepository
     */
    public function __construct(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }


    /**
     * @Route("/genre", name="genre")
     */
    public function index()
    {
        $genres = $this->genreRepository->findAll();
        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/genre/ajouter", name="ajouter_genre")
     * @param Genre|null $genre
     * @param Request $request
     * @param GenreService $genreService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajouterGenre(Genre $genre=null, Request $request, GenreService $genreService)
    {
        if (is_null($genre)) {
            $genre = new Genre();
        }
        $genreForm = $this->createForm(GenreFormType::class, $genre);
        $genreForm->handleRequest($request);

        if ($genreForm->isSubmitted() && $genreForm->isValid()) {
            $genreName = $genreForm->get('nom')->getData();
            $genres = $this->genreRepository->findAll();
            $existe = false;
            //check si le nom existe deja
            foreach ($genres as $genreNameExistant) {
                if ( $genreName === $genreNameExistant->getNom()) {
                    $existe = true;
                }
            }
            if (!is_null($genre) && !$existe) {
                $genreService->addGenre($genre);
                return $this->redirectToRoute('genre');
            } elseif ($existe) {
                $this->addFlash('warning', 'Ce genre existe déjà');

            }
        }


        return $this->render('genre/ajouter.html.twig', [
            'genre' => $genre,
            'genreForm' => $genreForm->createView()
        ]);
    }
}

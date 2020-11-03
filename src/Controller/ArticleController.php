<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Service\ArticleService;
use App\Service\CommentaireService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @var CommentaireRepository
     */
    private $commentaireRepository;
    /**
     * @var CommentaireService
     */
    private $commentaireService;
    /**
     * @var ArticleRepository
     */
    private $repository;
    /**
     * @var ArticleService
     */
    private $articleService;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ArticleController constructor.
     * @param CommentaireRepository $commentaireRepository
     * @param CommentaireService $commentaireService
     * @param ArticleRepository $repository
     * @param ArticleService $articleService
     * @param SerializerInterface $serializer
     */
    public function __construct(CommentaireRepository $commentaireRepository,
                                CommentaireService $commentaireService,
                                ArticleRepository $repository,
                                ArticleService $articleService, SerializerInterface $serializer)
    {
        $this->commentaireRepository = $commentaireRepository;
        $this->commentaireService = $commentaireService;
        $this->repository = $repository;
        $this->articleService = $articleService;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/article", name="article")
     */
    public function index(Request $request)
    {
        $pageNumber = $request->get('page')?? 1;
        $articles = $this->repository->findAllArticlesPaginate($pageNumber, Article::MAX_PER_PAGE);
        $commentaires = $this->commentaireRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'commentaires' => $commentaires
        ]);
    }

    /**
     * @Route("/article/creation", name="creation_article")
     * @Route("/article//modifier/{id}", name="modifier_article", requirements={"id":"\d+"})
     * @param Article|null $article
     * @param Request $request
     * @param ArticleService $articleService
     * @return Response
     */
    public function addOrEditArticle(Article $article=null, Request $request, ArticleService $articleService)
    {
        $ajout = false;
        $lastImage = null;
        $commentaires = $this->commentaireRepository->findAll();
        if (!is_null($article) && !is_null($article->getImage())) {
            $lastImage = $article->getImage();
        }
        if (is_null($article)) {
            $article = new Article();
            $ajout = true;
        }

        $articleForm = $this->createForm(ArticleFormType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $imageFile = $articleForm->get('image')->getData();
            if ($imageFile) {
                $extension = $imageFile->guessExtension();
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;
                $imageFile->move(
                    $this->getParameter('upload_directory_article_photo'),
                    $newFilename
                );
                $article->setImage($newFilename);
            } elseif (is_null($articleForm->get('image')->getData())) {
                $article->setImage($lastImage);
            }

            $articleService->addOrEdit($article, $ajout);
            $this->redirectToRoute('article');
            $this->addFlash('success', 'Nouvel article ajouté');
        }else {
            $this->addFlash('danger', 'Erreur lors de l\'enregistrement de l\'article');
        }
        return $this->render('article/new-article.html.twig', [
            'articleForm' => $articleForm->createView(),
            'article' => $article,
            'ajout' => $ajout,
            'commentaires' => $commentaires
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuFormType;
use App\Repository\LieuRepository;
use App\Service\LieuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @var LieuRepository
     */
    private $lieuRepository;
    /**
     * @var LieuService
     */
    private $lieuService;

    /**
     * LieuController constructor.
     * @param LieuRepository $lieuRepository
     * @param LieuService $lieuService
     */
    public function __construct(LieuRepository $lieuRepository, LieuService $lieuService)
    {
        $this->lieuRepository = $lieuRepository;
        $this->lieuService = $lieuService;
    }

    /**
     * @Route("/lieu", name="lieu")
     */
    public function index()
    {
        $lieux = $this->lieuRepository->findAll();
        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieux,
        ]);
    }

    /**
     * @Route("/lieu/creation", name="creation_lieu")
     * @Route("/lieu/modifier/{id}", name="modifier_lieu", requirements={"id":"\d+"})
     * @param Lieu|null $lieu
     * @param Request $request
     * @param bool $ajout
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajouterOuModifierLieu(Lieu $lieu=null, Request $request) {

        $ajout = false;
        if (is_null($lieu)) {
            $lieu = new Lieu();
            $ajout =true;
        }

        $lieuForm = $this->createForm(LieuFormType::class, $lieu);
        $lieuForm->handleRequest($request);

        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $this->lieuService->addOrEdit($lieu);
            $this->redirectToRoute('lieu');
            $this->addFlash('success', 'Nouveau lieu ajouté');
        } else {
            $this->addFlash('danger', 'Un problème est survenu');
        }

        return $this->render('lieu/add-or-edit.html.twig', [
            'lieuForm' => $lieuForm->createView(),
            'lieu' => $lieu,
            'ajout' => $ajout,

        ]);

    }

}

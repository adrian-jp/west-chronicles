<?php

namespace App\Controller;

use App\Entity\Clip;
use App\Form\ClipFormType;
use App\Repository\ClipRepository;
use App\Service\ClipService;
use App\Service\FileUploader;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClipController extends AbstractController
{
    /**
     * @var ClipRepository
     */
    private $clipRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ClipController constructor.
     * @param ClipRepository $clipRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(ClipRepository $clipRepository, SerializerInterface $serializer)
    {
        $this->clipRepository = $clipRepository;
        $this->serializer = $serializer;
    }


    /**
     * @Route("/clip", name="clip", options={"expose" = true})
     */
    public function index()
    {
        return $this->render('clip/index.html.twig', [
        ]);
    }

    /**
     * @Route("/clip-list", name="clip_list", options={"expose" = true})
     */
    public function clipList() {
        $clips = $this->clipRepository->findAll();
        $jsonClips = $this->serializer->serialize($clips, 'json');
        return new Response($jsonClips);
    }

    /**
     * @Route("/clip/creation", name="creation_clip", options={"expose" = true})
     * @param Clip|null $clip
     * @param Request $request
     * @param ClipService $clipService
     * @return RedirectResponse|Response
     */
    public function addOrEdit(Clip $clip=null, Request $request, ClipService $clipService) {
        $ajout = false;

        if (is_null($clip)) {
            $clip = new Clip();
            $ajout = true;
        }
        $formClip = $this->createForm(ClipFormType::class, $clip);
        $formClip->handleRequest($request);

        if ($formClip->isSubmitted() && $formClip->isValid()) {
            $clipFile = $formClip->get('url')->getData();
            if ($clipFile) {
                $clip->setUrl($clipFile);

            }else {
                $this->addFlash('danger', 'Problème lors du chargement du fichier audio');

            }
            $clipService->addOrEditClip($clip, $ajout);
            $this->addFlash('success', 'Clip ajouté');
            return $this->redirectToRoute('clip');
        }
        return $this->render('clip/creation-clip.html.twig', [
            'ajout' => $ajout,
            'formClip' => $formClip->createView()
        ]);

    }
}

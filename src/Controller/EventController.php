<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * EventController constructor.
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }


    /**
     * @Route("/event", name="event")
     */
    public function index()
    {
        $events = $this->eventRepository->findEventByDate();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/creation", name="creation_event")
     * @Route("/event/modifier/{id}", name="modifier_event", requirements={"id":"\d+"})
     * @param Event|null $event
     * @param Request $request
     * @param EventService $eventService
     */
    public function AjouterOuModifierEvent (Event $event=null, Request $request, EventService $eventService) {
        $ajout = false;
        $lastImage = null;

        if (!is_null($event) && !is_null($event->getImage())) {
            $lastImage = $event->getImage();
        }
        if (is_null($event)) {
            $event = new Event();
            $ajout = true;
        }

        $eventForm = $this->createForm(EventFormType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $imageFile = $eventForm->get('image')->getData();
            if ($imageFile) {
                $extension = $imageFile->guessExtension();
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;
                $imageFile->move(
                    $this->getParameter('upload_directory_event_photo'),
                    $newFilename
                );
                $event->setImage($newFilename);
            } elseif (is_null($eventForm->get('image')->getData())) {
                $event->setImage($lastImage);
            }
            $eventService->addOrEdit($event);
            $this->redirectToRoute('event');
            $this->addFlash('success', 'Nouvel évènement ajouté');
        }

        return $this->render('event/create-or-edit.html.twig', [
            'event' => $event,
            'eventForm' => $eventForm->createView(),
            'ajout' => $ajout
        ]);
    }
}

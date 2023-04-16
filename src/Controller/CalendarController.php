<?php

namespace App\Controller;

use App\Repository\MatiereRepository;
use App\Repository\CalendrierRepository;
use App\Repository\IntervenantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    #[Route('/get-alternance-weeks', name: 'get_alternances', methods: ['GET'])]
    public function getAlternance(CalendrierRepository $calendrierRepository): JsonResponse
    {
        $weeks = $calendrierRepository->findBy(['type' => 'alternance']);

        return $this->json($weeks);
    }

    #[Route('/get-cours-infos', name: 'get_cours_infos', methods: ['GET'])]
    public function addCoursTemplate(IntervenantRepository $intervenantRepository, MatiereRepository $matiereRepository): Response
    {
        $intervenants = $intervenantRepository->findAll();
        $matieres = $matiereRepository->findAll();

        return $this->render('template_cours_admin.html.twig', [
            'intervenants' => $intervenants,
            'matieres' => $matieres
        ]);
    }
}

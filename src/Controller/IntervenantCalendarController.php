<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\MatiereRepository;
use App\Repository\IntervenantRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/intervenant')]
class IntervenantCalendarController extends AbstractController
{
    #[Route('/', name: 'intervenant_page', methods: ['GET'])]
    public function intervenantPanel(): Response
    {
        return $this->render('intervenant_panel/index.html.twig');
    }

    #[Route('/calendrier/{year}', name: 'app_intervenant_calendar', methods: ['GET'])]
    public function showCalendar(int $year): Response
    {
        $annee = new \DateTime($year . '-08-31');
        $annee->modify('first monday');

        $annee_suivante = new \DateTime(($year + 1) . '-08-31');
        $annee_suivante->modify('first monday');

        $tableau_semaines = [$annee->format('Y-m-d')];
        while ($annee < $annee_suivante) {
            $tableau_semaines[] = $annee->modify('next monday')->format('Y-m-d');
        }
        array_pop($tableau_semaines);

        return $this->render("intervenant/calendrier.html.twig", ["tableauSemaines" => $tableau_semaines]);
    }

    #[Route('/get-cours', name: 'get_intervenant_cours', methods: ['GET'])]
    public function getCours(IntervenantRepository $intervenantRepository, Security $security, CoursRepository $coursRepository): JsonResponse
    {
        $intervenant = $intervenantRepository->findOneBy(['user' => $security->getUser()]);

        return $this->json($coursRepository->getCoursByIntervenants($intervenant));
    }

    #[Route('/get-cours-infos', name: 'intervenant_get_cours_infos', methods: ['GET'])]
    public function getAddCoursTemplate(IntervenantRepository $intervenantRepository, MatiereRepository $matiereRepository): Response
    {
        $intervenants = $intervenantRepository->findAll();
        $matieres = $matiereRepository->findAll();

        return $this->render('template_cours_admin.html.twig', [
            'intervenants' => $intervenants,
            'matieres' => $matieres
        ]);
    }
}

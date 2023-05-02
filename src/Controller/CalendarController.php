<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\MatiereRepository;
use App\Repository\CalendrierRepository;
use App\Repository\CoursRepository;
use App\Repository\IntervenantRepository;
use Symfony\Component\HttpFoundation\Request;
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
    public function getAddCoursTemplate(IntervenantRepository $intervenantRepository, MatiereRepository $matiereRepository): Response
    {
        $intervenants = $intervenantRepository->findAll();
        $matieres = $matiereRepository->findAll();

        return $this->render('template_cours_admin.html.twig', [
            'intervenants' => $intervenants,
            'matieres' => $matieres
        ]);
    }

    #[Route('/get-cours', name: 'get_cours', methods: ['GET'])]
    public function getCours(CoursRepository $coursRepository): JsonResponse
    {
        $cours = $coursRepository->findAll();

        return $this->json($cours);
    }

    #[Route('/add-cours', name: 'add_cours', methods: ['POST'])]
    public function addCours(Request $request, CoursRepository $coursRepository, MatiereRepository $matiereRepository): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        $matiere = $matiereRepository->find($data['id_matiere']);

        $dateHeureCourDebut = new \DateTime($data['dateHeureCour_debut']);
        $dateHeureCourFin = new \DateTime($data['dateHeureCour_fin']);
        $interval = $dateHeureCourDebut->diff($dateHeureCourFin);
        $minutesToAdd = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

        if ($coursRepository->getMinutesConsumedByMatiere($matiere)['minutes'] + $minutesToAdd > $matiere->getNbHours() * 60) {
            return $this->json(["status" => "error_hours"], 400);
        }


        if ($form->isValid()) {
            $coursRepository->save($cours, true);

            return $this->json(["status" => "ok", "title" => $matiere->getIntervenant()->getFullName() . ' - ' . $matiere->getFullName()]);
        }

        return $this->json(["status" => "error"], 400);
    }
}

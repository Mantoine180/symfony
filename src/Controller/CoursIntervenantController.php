<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use App\Repository\IntervenantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/intervenant/cours')]
class CoursIntervenantController extends AbstractController
{
    #[Route('/', name: 'app_cours_index_intervenant', methods: ['GET'])]
    public function index(IntervenantRepository $intervenantRepository, Security $security, CoursRepository $coursRepository): Response
    {
        $intervenant = $intervenantRepository->findOneBy(['user' => $security->getUser()]);

        return $this->render('cours_intervenant/index.html.twig', [
            'cours' => $coursRepository->getCoursByIntervenants($intervenant),
        ]);
    }

    #[Route('/{id_cours}', name: 'app_cours_delete_intervenant')]
    public function delete(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        $coursRepository->remove($cour, true);

        return $this->redirectToRoute('app_cours_index_intervenant', [], Response::HTTP_SEE_OTHER);
    }
}

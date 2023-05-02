<?php

namespace App\Controller;

use App\Entity\IntervenantDisponibility;
use App\Form\IntervenantDisponibilityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IntervenantDisponibilityRepository;
use App\Repository\IntervenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/intervenant/disponibility')]
class IntervenantDisponibilityController extends AbstractController
{
    #[Route('/', name: 'app_intervenant_disponibility_index', methods: ['GET'])]
    public function index(
        Security $security,
        IntervenantDisponibilityRepository $intervenantDisponibilityRepository,
        IntervenantRepository $intervenantRepository
    ): Response {
        $user = $security->getUser();
        $intervenant = $intervenantRepository->findOneBy(["user" => $user]);
        $intervenantDisponibilities = $intervenantDisponibilityRepository->findBy(["intervenant" => $intervenant]);

        return $this->render('intervenant_disponibility/index.html.twig', [
            'intervenant_disponibilities' => $intervenantDisponibilities,
        ]);
    }

    #[Route('/new', name: 'app_intervenant_disponibility_new', methods: ['GET', 'POST'])]
    public function new(
        Security $security,
        Request $request,
        IntervenantDisponibilityRepository $intervenantDisponibilityRepository,
        IntervenantRepository $intervenantRepository
    ): Response {
        $intervenantDisponibility = new IntervenantDisponibility();
        $form = $this->createForm(IntervenantDisponibilityType::class, $intervenantDisponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $intervenant = $intervenantRepository->findOneBy(["user" => $user]);
            $intervenantDisponibility->setIntervenant($intervenant);

            $intervenantDisponibilityRepository->save($intervenantDisponibility, true);


            return $this->redirectToRoute('app_intervenant_disponibility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervenant_disponibility/new.html.twig', [
            'intervenant_disponibility' => $intervenantDisponibility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intervenant_disponibility_show', methods: ['GET'])]
    public function show(IntervenantDisponibility $intervenantDisponibility): Response
    {
        return $this->render('intervenant_disponibility/show.html.twig', [
            'intervenant_disponibility' => $intervenantDisponibility,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_intervenant_disponibility_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IntervenantDisponibility $intervenantDisponibility, IntervenantDisponibilityRepository $intervenantDisponibilityRepository): Response
    {
        $form = $this->createForm(IntervenantDisponibilityType::class, $intervenantDisponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $intervenantDisponibilityRepository->save($intervenantDisponibility, true);

            return $this->redirectToRoute('app_intervenant_disponibility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervenant_disponibility/edit.html.twig', [
            'intervenant_disponibility' => $intervenantDisponibility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intervenant_disponibility_delete', methods: ['POST'])]
    public function delete(Request $request, IntervenantDisponibility $intervenantDisponibility, IntervenantDisponibilityRepository $intervenantDisponibilityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $intervenantDisponibility->getId(), $request->request->get('_token'))) {
            $intervenantDisponibilityRepository->remove($intervenantDisponibility, true);
        }

        return $this->redirectToRoute('app_intervenant_disponibility_index', [], Response::HTTP_SEE_OTHER);
    }
}

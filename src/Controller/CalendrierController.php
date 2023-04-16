<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Form\CalendrierType;
use App\Repository\CalendrierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/calendrier')]
class CalendrierController extends AbstractController
{
    #[Route('/', name: 'app_calendrier_index', methods: ['GET'])]
    public function index(CalendrierRepository $calendrierRepository): Response
    {
        return $this->render('calendrier/index.html.twig', [
            'calendriers' => $calendrierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_calendrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CalendrierRepository $calendrierRepository): Response
    {
        $calendrier = new Calendrier();
        $form = $this->createForm(CalendrierType::class, $calendrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendrierRepository->save($calendrier, true);

            return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendrier/new.html.twig', [
            'calendrier' => $calendrier,
            'form' => $form,
        ]);
    }

    #[Route('/{id_calendrier}', name: 'app_calendrier_show', methods: ['GET'])]
    public function show(Calendrier $calendrier): Response
    {
        return $this->render('calendrier/show.html.twig', [
            'calendrier' => $calendrier,
        ]);
    }

    #[Route('/{id_calendrier}/edit', name: 'app_calendrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendrier $calendrier, CalendrierRepository $calendrierRepository): Response
    {
        $form = $this->createForm(CalendrierType::class, $calendrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendrierRepository->save($calendrier, true);

            return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendrier/edit.html.twig', [
            'calendrier' => $calendrier,
            'form' => $form,
        ]);
    }

    #[Route('/{id_calendrier}', name: 'app_calendrier_delete', methods: ['POST'])]
    public function delete(Request $request, Calendrier $calendrier, CalendrierRepository $calendrierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendrier->getId_calendrier(), $request->request->get('_token'))) {
            $calendrierRepository->remove($calendrier, true);
        }

        return $this->redirectToRoute('app_calendrier_index', [], Response::HTTP_SEE_OTHER);
    }
}

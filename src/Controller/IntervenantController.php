<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Intervenant;
use App\Form\IntervenantFormType;
use App\Form\RegistrationFormType;
use App\Form\UserFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IntervenantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/admin/intervenant')]
class IntervenantController extends AbstractController
{
    #[Route('/', name: 'app_intervenant_index', methods: ['GET'])]
    public function index(IntervenantRepository $intervenantRepository): Response
    {
        return $this->render('intervenant/index.html.twig', [
            'intervenants' => $intervenantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_intervenant_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_INTERVENANT"]);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $intervenant = new Intervenant();
            $intervenant->setUser($user);
            

            $entityManager->persist($user);
            $entityManager->persist($intervenant);
            $entityManager->flush();

            return $this->redirectToRoute('app_intervenant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id_intervenant}', name: 'app_intervenant_show', methods: ['GET'])]
    public function show(Intervenant $intervenant): Response
    {
        return $this->render('intervenant/show.html.twig', [
            'intervenant' => $intervenant,
        ]);
    }

    #[Route('/{id_intervenant}/edit', name: 'app_intervenant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Intervenant $intervenant, IntervenantRepository $intervenantRepository): Response
    {
        $form = $this->createForm(UserFormType::class, $intervenant->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $intervenantRepository->save($intervenant, true);

            return $this->redirectToRoute('app_intervenant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervenant/edit.html.twig', [
            'intervenant' => $intervenant,
            'form' => $form,
        ]);
    }

    #[Route('/{id_intervenant}', name: 'app_intervenant_delete', methods: ['POST'])]
    public function delete(Request $request, Intervenant $intervenant, IntervenantRepository $intervenantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $intervenant->getIdIntervenant(), $request->request->get('_token'))) {
            $intervenantRepository->remove($intervenant, true);
        }

        return $this->redirectToRoute('app_intervenant_index', [], Response::HTTP_SEE_OTHER);
    }
}

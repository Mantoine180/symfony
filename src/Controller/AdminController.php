<?php

namespace App\Controller;

use App\Entity\IntervenantDisponibility;
use App\Repository\CoursRepository;
use App\Repository\IntervenantDisponibilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_page')]
    public function home()
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/disponibilities', name: 'all_disponibility')]
    public function disponibilities(IntervenantDisponibilityRepository $intervenantDisponibilityRepository)
    {
        return $this->render('admin/disponibilities.html.twig', [
            'disponibilities' =>  $intervenantDisponibilityRepository->findBy([], ['intervenant' => 'ASC'])
        ]);
    }
}
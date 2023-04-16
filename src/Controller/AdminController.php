<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route ("/admin", name="admin_page")
     */
    public function home()
    {
        return $this->render('admin/index.html.twig');
    }

}
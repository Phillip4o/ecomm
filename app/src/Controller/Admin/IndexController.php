<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('admin/base/base.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}

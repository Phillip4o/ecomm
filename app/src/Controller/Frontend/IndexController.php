<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('frontend/base/base.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}

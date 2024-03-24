<?php

namespace App\Controller\Frontend\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function show(
        #[MapEntity(expr: 'repository.findOneByUrl(url)')]
        Category $category,
        CategoryRepository $categoryRepository): Response
    {
        return $this->render('frontend/category/show.html.twig', [
            'category' => $category,
        ]);
    }
}

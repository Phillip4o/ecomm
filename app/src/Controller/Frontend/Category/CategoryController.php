<?php

namespace App\Controller\Frontend\Category;

use App\Entity\Category;
use App\Repository\Elastic\ElasticCategoryRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function show(
        #[MapEntity(expr: 'repository.findOneByUrl(url)')]
        Category $category,
        Request $request,
        ElasticCategoryRepository $repository
    ): Response
    {
        $pagerfanta = $repository->findProductsPaginated(
            $category->getId(),
            $request->query->getInt('page', 1)
        );

        return $this->render('frontend/category/show.html.twig', [
            'category' => $category,
            'products' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
        ]);
    }
}

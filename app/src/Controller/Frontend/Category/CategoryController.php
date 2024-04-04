<?php

namespace App\Controller\Frontend\Category;

use App\Entity\Category;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class CategoryController extends AbstractController
{
    private PaginatedFinderInterface $finder;

    public function __construct(PaginatedFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    public function show(
        #[MapEntity(expr: 'repository.findOneByUrl(url)')]
        Category $category,
        Request $request
    ): Response
    {
        $boolQuery = new BoolQuery();

        $categoryQuery = new Terms('categories.id', [$category->getId()]);
        $boolQuery->addMust($categoryQuery);

        $adapter = $this->finder->createRawPaginatorAdapter($boolQuery);
        $pagerfanta = new Pagerfanta(new FantaPaginatorAdapter($adapter));

        $pagerfanta->setMaxPerPage(1);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        return $this->render('frontend/category/show.html.twig', [
            'category' => $category,
            'products' => $pagerfanta->getCurrentPageResults(),
            'pager' => $pagerfanta,
        ]);
    }
}

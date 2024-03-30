<?php

namespace App\Controller\Frontend\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
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
        CategoryRepository $categoryRepository,
        Request $request
    ): Response
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $categoryQuery = new \Elastica\Query\Terms('categories.id', [$category->getId()]);
        $boolQuery->addMust($categoryQuery);

        $paginatorAdapter = $this->finder->createRawPaginatorAdapter($boolQuery);

        // Create the ElasticaAdapter for Pagerfanta
        $adapter = new FantaPaginatorAdapter($paginatorAdapter);

        // Create the Pagerfanta instance
        $pagerfanta = new Pagerfanta($adapter);

        // Set the current page from the request, default to 1
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        // Set items per page
        $pagerfanta->setMaxPerPage(1); // Adjust as needed

        // Get the current page of results
        $currentPageResults = $pagerfanta->getCurrentPageResults();


//        dd($currentPageResults);

        return $this->render('frontend/category/show.html.twig', [
            'category' => $category,
            'products' => $currentPageResults,
            'pager' => $pagerfanta,
        ]);
    }
}

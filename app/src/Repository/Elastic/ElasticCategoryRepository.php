<?php

namespace App\Repository\Elastic;

use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Pagerfanta;

class ElasticCategoryRepository
{
    public function __construct(private readonly PaginatedFinderInterface $finder)
    {
    }

    public function findProductsPaginated(int $categoryId, int $page): Pagerfanta
    {
        $boolQuery = new BoolQuery();

        $categoryQuery = new Terms('categories.id', [$categoryId]);
        $boolQuery->addMust($categoryQuery);

        $adapter = $this->finder->createRawPaginatorAdapter($boolQuery);
        $pagerfanta = new Pagerfanta(new FantaPaginatorAdapter($adapter));

        $pagerfanta->setMaxPerPage(1);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
    }
}
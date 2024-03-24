<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private FactoryInterface $factory;

    private CategoryRepository $categoryRepository;

    public function __construct(FactoryInterface $factory, CategoryRepository $categoryRepository)
    {
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'index_frontend']);
        $menu->addChild('Admin', ['route' => 'index_admin']);

        $categories = $this->categoryRepository->findRootCategories();
        $this->addMenuItems($menu, $categories);

        return $menu;
    }

    private function addMenuItems($menu, $categories): void
    {
        /** @var Category $category */
        foreach ($categories as $category) {
            $child = $menu->addChild($category->getName(), [
                'route' => 'category_show',
                'routeParameters' => [
                    'url' => $category->getUrl()
                ]
            ]);

            if ($category->hasSubCategories()) {
                $this->addMenuItems($child, $category->getSubCategories());
            }
        }
    }
}

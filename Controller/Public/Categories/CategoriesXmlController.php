<?php

namespace BaksDev\Avito\Board\Controller\Public\Categories;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Products\Category\Repository\AllCategoryByMenu\AllCategoryByMenuInterface;
use BaksDev\Products\Product\Repository\AllProductsByCategory\AllProductsByCategoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class CategoriesXmlController extends AbstractController
{
    #[Route('/avito-board/categories.xml', name: 'public.export.products', methods: ['GET'])]
    public function products(
        AllCategoryByMenuInterface     $activeCategory,
        AllProductsByCategoryInterface $productsByCategory
    ): Response {

        $category = $activeCategory->findAll();
        $products = $productsByCategory->fetchAllProductByCategory();

        $product = [$products[0]];

        $response = $this->render(
            [
                'category' => $category,
                'products' => $product,
            ],
            file: 'export.html.twig'
        );

        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }
}

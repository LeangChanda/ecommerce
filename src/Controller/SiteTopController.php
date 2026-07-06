<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class SiteTopController extends AbstractController
{
    #[Route('/', name: 'site_top', methods: ['GET'])]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $latestProducts = $productRepository->findLatestProducts();

        $featuredProducts = $productRepository->findFeaturedProducts();

        $categories = $categoryRepository->findBy(
            ['isActive' => true],
            ['name' => 'ASC']
        );

        $categoryProducts = [];

        foreach ($categories as $category) {
            $categoryProducts[$category->getId()] =
                $productRepository->findByCategoryLimit($category, 5);
        }

        return $this->render('site_top/index.html.twig', [
            'latestProducts' => $latestProducts,
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'categoryProducts' => $categoryProducts,
        ]);
    }
}
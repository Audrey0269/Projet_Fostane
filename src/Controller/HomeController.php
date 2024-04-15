<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/voir-detail-product/{id}', name: 'app_user_product_show' , methods:['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('home/show.html.twig', [
            'product' => $product,
        ]);
    }
}

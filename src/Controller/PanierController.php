<?php

namespace App\Controller;

use App\Services\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Panier $panier): Response
    {
        return $this->render('panier/index.html.twig', 
        [
            'panier' => $panier -> getDetailPanier(),
            'total_panier' => $panier -> getTotal(),
            'totalHt_panier' => $panier -> getTotalHt(),
            'totalTva_panier' => $panier -> getTotalTva(),
        ]);
    }

    #[Route('/ajouter-panier/{id}', name: 'app_panier_add_product')]
    public function add($id , Panier $panier): Response
    {
        $panier -> addProductPanier($id);
        return $this -> redirectToRoute('app_panier');
    }


    #[Route('/delete-quantite-panier/{id}', name: 'app_panier_delete_quantity')]
    public function deleteQuantity($id , Panier $panier): Response
    {
        $panier -> deleteQuantityProduct($id);
        return $this -> redirectToRoute('app_panier');
    }


    #[Route('/delete-product-panier/{id}', name: 'app_panier_delete_product')]
    public function deleteProduct($id , Panier $panier): Response
    {
        $panier -> deleteProductPanier($id);
        return $this -> redirectToRoute('app_panier');
    }
}

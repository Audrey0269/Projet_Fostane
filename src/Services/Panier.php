<?php

namespace App\Services;

use App\Repository\ProductRepository;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Panier
{

    private $requestStack;
    private $productRepository;


    public  function __construct(RequestStack $requestStack , ProductRepository $productRepository)
    {
        $this -> requestStack = $requestStack;
        $this -> productRepository = $productRepository;
    }

    private function getSession()
    {
        return $this->requestStack->getSession();
    }


    /**
     * Récupère le panier sinon crée un tableau vide nommé panier
     *
     * @return array
     */
    public function getPanier(){
        return $this -> getSession() -> get('panier' , []) ;
    }


    public function addProductPanier(int $id){
        $panier = $this->getPanier();

        if (!empty($panier[$id])){
            $panier[$id] = $panier[$id] + 1 ;   //  OU $panier [$id] ++ OU +=1 ;
        }else{
            $panier [$id] = 1;
        }
        $this -> getSession() -> set('panier' , $panier);
    }


    public function deleteProductPanier($id){
        $panier = $this -> getPanier();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this -> getSession() -> set('panier' , $panier);
    }


    public function deleteQuantityProduct($id){
        $panier = $this -> getPanier();
        
        if($panier[$id] > 1){
            $panier[$id]  = $panier[$id] - 1 ;
        }else{
            unset($panier[$id]);
        }
        $this -> getSession() -> set('panier' , $panier);
    }

    public function deletePanier(){
        $this -> getSession() -> remove('panier');
    }


    public function getDetailPanier(){
        $panier = $this -> getPanier(); //on recupère le panier
        $panier_detail = [] ;
        foreach ($panier as $id => $quantity) {
            $product = $this -> productRepository -> find($id);

            $tva = $product ->getTva() -> getTaux();
            $prix_unit = $product -> getPrix();
            $totalTtc = $prix_unit * $quantity;
            $totalHt = $totalTtc / (1 + $tva);
            $totalTva = $totalTtc - $totalHt;

            $panier_detail [] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $totalTtc,
                'totalHt' => $totalHt,
                'totalTva' => $totalTva
            ];
        }
        return $panier_detail;
    }



    public function getTotal(){
        $panier = $this -> getDetailPanier();
        $total = 0 ;
        foreach ($panier as $row) {
            $total = $total + $row['total'];
        }
        return $total ;
    }


    public function getTotalHt(){
        $panier = $this -> getDetailPanier();
        $total = 0 ;
        foreach ($panier as $row) {
            $total = $total + $row['totalHt'];
        }
        return $total ;
    }


    public function getTotalTva(){
        $panier = $this -> getDetailPanier();
        $total = 0 ;
        foreach ($panier as $row) {
            $total = $total + $row['totalTva'];
        }
        return $total ;
    }


}

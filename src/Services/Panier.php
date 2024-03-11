<?php

namespace App\Services;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{

    private $session;
    private $produitRepository;


    public  function __construct(SessionInterface $sessionInterface , ProduitRepository $produitRepository)
    {
        $this -> session = $sessionInterface;
        $this -> produitRepository = $produitRepository;
    }


    /**
     * Récupère le panier sinon crée un tableau vide nommé panier
     *
     * @return array
     */
    public function getPanier(){
        return $this -> session -> get('panier' , []) ;
    }


    public function addProduitPanier(int $id){
        $panier = $this->getPanier();

        if (!empty($panier[$id])){
            $panier[$id] = $panier[$id] + 1 ;   //  OU $panier [$id] ++ OU +=1 ;
        }else{
            $panier [$id] = 1;
        }
        $this -> session -> set('panier' , $panier);
    }


    public function deleteProduitPanier($id){
        $panier = $this -> getPanier();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this -> session -> set('panier' , $panier);
    }


    public function deleteQuantityProduit($id){
        $panier = $this -> getPanier();
        
        if($panier[$id] > 1){
            $panier[$id]  = $panier[$id] - 1 ;
        }else{
            unset($panier[$id]);
        }
        $this -> session -> set('panier' , $panier);
    }

    public function deletePanier(){
        $this -> session -> remove('panier');
    }


    public function getDetailPanier(){
        $panier = $this -> getPanier(); //on recupère le panier
        $panier_detail = [] ;
        foreach ($panier as $id => $quantity) {
            $produit = $this -> produitRepository -> find($id);

            $tva = $produit ->getTva() -> getTaux();
            $prix_unit = $produit -> getPrix();
            $totalTtc = $prix_unit * $quantity;
            $totalHt = $totalTtc / (1 + $tva);
            $totalTva = $totalTtc - $totalHt;

            $panier_detail [] = [
                'produit' => $produit,
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

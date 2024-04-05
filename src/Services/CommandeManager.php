<?php
namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Entity\Commande;
use App\Services\Panier;
use App\Entity\DetailCommande;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class CommandeManager
{
    private $security;

    public function __construct(SecurityRequestAttributes $security)
    {
        $this -> security = $security;
    }


    // /**
    //  * Undocumented function
    //  *
    //  * @return User|null
    //  */
    // public function getUser() : ?User
    // {
    //     return $this -> security -> getUser();
    // }


    // public function getCommande(Panier $panier):Commande
    // {
    //     $commande = new Commande();
    //     $user = $this -> getUser();
    //     $commande -> setUser ($user);
    //     $commande -> setName ($user -> getNom() . ' ' . $user -> getPrenom());
    //     $commande -> setAdresse ($user -> getAdresse());
    //     $commande -> setTotal($panier -> getTotal());
    //     $commande -> setTva($panier -> getTotalTva());
    //     $commande -> setHt($panier -> getTotalHt());
    //     //je recup la date du jour
    //     $date_jour = new DateTime();

    //     $commande -> setDateCommande($date_jour);
    //     $commande -> setLivraison(false);

    //     return $commande;
    // }


    public function getDetailCommande(Commande $commande , $ligne_panier){
        $detailCommande = new DetailCommande();
        $detailCommande -> setCommande($commande);

        //Récupérer la ligne du panier
        $detailCommande -> setName ($ligne_panier['produit']->getName());

        $detailCommande -> setRef ($ligne_panier['produit'] -> getRef());
        $detailCommande -> setPrixUnit ($ligne_panier['produit'] -> getPrix());
        $detailCommande -> setQuantity ($ligne_panier['quantity']);
        $detailCommande -> setTotal ($ligne_panier['total']);

        $detailCommande -> setHt ($ligne_panier['totalHt']);
        $detailCommande -> setTva ($ligne_panier['totalTva']);
        $detailCommande -> setTauxTva (($ligne_panier['produit'] -> getTva() -> getTaux() * 100) . ' %');

        return $detailCommande;
    }

}
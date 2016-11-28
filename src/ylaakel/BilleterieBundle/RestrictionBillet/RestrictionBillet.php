<?php

namespace ylaakel\BilleterieBundle\RestrictionBillet;
use Doctrine\ORM\EntityManager;
use ylaakel\BilleterieBundle\Entity\Commande;

class RestrictionBillet
{
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

	public function nbrBilletIsTooMuch(Commande $commande) {
        //On récupère toutes les commandes à la date choisie par l'utilisateur et qui sont payés
        $commandesDate = $this->em->getRepository('ylaakelBilleterieBundle:Commande')->commandesDate($commande->getLaDate());
        $allBillets = 0;
        //On compte le nombre de billets vendu à cette date
        // à revoir
        foreach ($commandesDate as $uneCommande) {
            $allBillets += $uneCommande->getNbrBillet();
        }
        //il y a + de 1000 billets vendus déjà, ou alors la personne souhaite commander + de 1000 billets
        if($allBillets > 1000 || $commande->getNbrBillet() > 1000) {
        	return true;
        }
        else {
        	return false;
        }
	}

}

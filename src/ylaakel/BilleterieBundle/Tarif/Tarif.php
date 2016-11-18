<?php

namespace ylaakel\BilleterieBundle\Tarif;
use ylaakel\BilleterieBundle\Entity\Commande;

class Tarif
{
	public function calculTarif(Commande $commande) {
		$prixTotal = 0;
	    foreach($commande->getInfoBillets() as $infoBillet) {
            $currentDate = date_create();
            $dateInfoBillet = $infoBillet->getDateNaissance();
            //Age de la personne
            $diff = date_diff($currentDate, $dateInfoBillet)->format('%Y');
            //Si la personne dispose du tarif réduit
            if($infoBillet->getTarifReduit()) {
                $prixTotal += 10;
                $infoBillet->setPrix(10);
            }
            else {
                if($diff < 4) {
                    $prixTotal += 0;  
                    $infoBillet->setPrix(0);  
                }
                elseif ($diff < 12) {
                    $prixTotal += 8;
                    $infoBillet->setPrix(8);  
                }
                elseif ($diff < 60) {
                    $prixTotal += 16;
                    $infoBillet->setPrix(16);  
                }
                else {
                    $prixTotal += 12;
                    $infoBillet->setPrix(12);  
                }
            }
        }
        return array('commande' => $commande, 'prixTotal' => $prixTotal);
	}
}

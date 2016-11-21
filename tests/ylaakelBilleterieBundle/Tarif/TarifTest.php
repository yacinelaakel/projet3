<?php 

namespace tests\ylaakelBilleterieBundle\Tarif;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Entity\InfoBillet;

class TarifTest extends WebTestCase {

	private $tarif;

	public function testCalculTarif() {
        $client = static::createClient();

		$commandeTest = new Commande();
		$unBilletTest = new InfoBillet();

		$commandeTest->setTypeBillet(1);
		//tarif plein et âge = 21 donc prix = 16
		$unBilletTest->setDateNaissance(new \DateTime('28-06-1995'));
		$commandeTest->addInfoBillet($unBilletTest);
		$tarif = $client->getContainer()->get('ylaakel_billeterie.tarif');
        $commandeEtPrix = $tarif->calculTarif($commandeTest);

        $unAutreBilletTest = new InfoBillet();
        //âge = 2 donc prix = 0 même si tarif réduit sélectionné
        $unAutreBilletTest->setDateNaissance(new \DateTime('28-06-2014'));
        $unAutreBilletTest->setTarifReduit(true);
        $commandeTest->addInfoBillet($unAutreBilletTest);

        $this->assertEquals(16, $commandeEtPrix['prixTotal']);


	}
}

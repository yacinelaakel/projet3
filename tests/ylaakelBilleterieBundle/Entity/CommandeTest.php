<?php

// tests/ylaakelBilleterieBundle/Entity/CommandeTest.php
namespace tests\ylaakelBilleterieBundle\Entity;

use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Entity\InfoBillet;

class CommandeTest extends \PHPUnit_Framework_TestCase
{
    public function testSetTypeBillet() {
        $commande = new Commande();
        $commande->setTypeBillet(true);
        $this->assertTrue(true, $commande->getTypeBillet());
    }

    public function testSetLaDate() {
    	$commande = new Commande();
    	$commande->setLaDate('18-08-2017');
    	$this->assertEquals('18-08-2017', $commande->getLaDate());
    }

    public function testTypeNbrBillet() {
    	$commande = new Commande();
    	$commande->setNbrBillet(50);
    	$nbr = $commande->getNbrBillet();
    	$this->assertTrue(true, is_int($nbr));
    }

    public function testClassInfoBillet() {
    	$commande = new Commande();
    	$infoBillet = new InfoBillet();
    	$commande->addInfoBillet($infoBillet);
    	$this->assertTrue(true, is_array($commande->getInfoBillets()));
    }

    public function testLengthNumCommande() {
    	$commande = new Commande();
    	$commande->setNumCommande(rand(1,10000) . 'aze' . rand(1, 10000) . 'rty');
    	$longueurNumCommande = strlen($commande->getNumCommande());
    	$result = 16 - $longueurNumCommande;
    	$this->assertTrue(true, $result >= 0);
    }
}
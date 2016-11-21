<?php

namespace tests\ylaakelBilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Entity\InfoBillet;
use Doctrine\ORM\EntityManager;

class BilleterieControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/');

        $this->assertEquals(1, $crawler->filter('h1:contains("Bienvenue sur le site de réservation de billets du Louvre !")')->count());
        
		$link = $crawler->filter('a:contains("achète mon billet")')->first()->link();
		$crawler = $client->click($link);
    	$this->assertEquals(1, $crawler->filter('h1:contains("Choix des billets")')->count());

    	$link = $crawler->filter('a:contains("Contact")')->first()->link();
   		$crawler = $client->click($link);
   	    $this->assertEquals(1, $crawler->filter('h1:contains("Formulaire de contact")')->count());
    }

    public function testCommande()
    {

        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/fr/commande-billet');

       
        $form = $crawler->selectButton('Etape suivante')->form();

        $form['ylaakel_billeteriebundle_commande_billet[laDate]'] = '16-11-2017';
        $form['ylaakel_billeteriebundle_commande_billet[typeBillet]'] = 1;
        $form['ylaakel_billeteriebundle_commande_billet[nbrBillet]'] = 1;
        $form['ylaakel_billeteriebundle_commande_billet[email]'] = 'yacine.laakel@hotmail.fr';

        $crawler = $client->submit($form);
		$this->assertEquals(1, $crawler->filter('h1:contains("Informations sur les billets")')->count());

    }

    public function testInfo() 
    {
        $client = static::createClient();
        $client->followRedirects();

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');

        $commandeTest = $em->getRepository('ylaakelBilleterieBundle:Commande')->find(297);

        $crawler = $client->request('GET', '/fr/information-billet-' . $commandeTest->getNumCommande());

        $this->assertEquals(1, $crawler->filter('h1:contains("Informations sur les billets")')->count());

        foreach ($commandeTest->getInfoBillets() as $infoBillet) {
                $commandeTest->removeInfoBillet($infoBillet);
        }        

        $unBillet = new InfoBillet();
        $commandeTest->addInfoBillet($unBillet);

        $unBillet->setNom('laakel');
        $unBillet->setPrenom('yacine');
        $unBillet->setPays('FR');
        $unBillet->setDateNaissance(new \DateTime('28-06-1995'));
        $unBillet->setTarifReduit(0);

        foreach ($commandeTest->getInfoBillets() as $infoBillet) {
                $em->persist($infoBillet);
        }
        $em->flush();

        $crawler = $client->request('GET', '/fr/paiement-billet-' . $commandeTest->getNumCommande());
        $this->assertEquals(1, $crawler->filter('h1:contains("Paiement de vos billets")')->count());
    }


    public function testContact()
	{
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/contact');

        $form = $crawler->selectButton('Envoyer')->form();

        $form['ylaakel_billeteriebundle_contact[name]'] = 'Yacine Laakel';
        $form['ylaakel_billeteriebundle_contact[email]'] = 'yacine.laakel@hotmail.fr';
        $form['ylaakel_billeteriebundle_contact[message]'] = 'Voici un message de test pour la page de contact';

	    // On vérifie que l'email a bien été envoyé
	    if ($profile = $client->getProfile())
	    {
	        $swiftMailerProfiler = $profile->getCollector('swiftmailer');

	        // Seul 1 message doit avoir été envoyé
	        $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

	        // On récupère le premier message
	        $messages = $swiftMailerProfiler->getMessages();
	        $message  = array_shift($messages);

	        $theEmail = $client->getContainer()->getParameter('mailer_user');
        	// On vérifie que le message a été envoyé à la bonne adresse
        	$this->assertArrayHasKey($theEmail, $message->getTo());
    	}

	    // On suit la redirection
	    $crawler = $client->submit($form);
 		$crawler = $client->followRedirect();

		$this->assertEquals(1, $crawler->filter('html:contains("Message bien envoyé.")')->count());
	}

}
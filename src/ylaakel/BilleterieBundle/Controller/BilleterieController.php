<?php

namespace ylaakel\BilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ylaakel\BilleterieBundle\Form\ContactType;
use ylaakel\BilleterieBundle\Entity\Contact;
use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Form\CommandeStep1Type;
use ylaakel\BilleterieBundle\Form\CommandeStep2Type;
use ylaakel\BilleterieBundle\Entity\InfoBillet;


class BilleterieController extends Controller
{
    public function indexAction()
    {
        return $this->render('ylaakelBilleterieBundle:Billeterie:index.html.twig');
    }

    public function contactAction(Request $request) {
    	$contact = new Contact();
    	$form = $this->createForm(ContactType::class, $contact);

    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

    		$name = $contact->getName();
    		$email = $contact->getEmail();

    		//Envoi du mail ici
    		$message = \Swift_Message::newInstance()
            ->setSubject('Formulaire de contact')
            ->setFrom(array($email => $name))
            ->setTo('yacine.laakel@hotmail.fr')
            ->setBody($this->renderView('ylaakelBilleterieBundle:Billeterie:contactEmail.txt.twig', array('contact' => $contact)));
        	$this->get('mailer')->send($message);

      		$request->getSession()->getFlashBag()->add('notice', 'Message bien envoyé.');

      		return $this->redirectToRoute('ylaakel_billeterie_contact');
    	}	


    	return $this->render('ylaakelBilleterieBundle:Billeterie:contact.html.twig', array('form' => $form->createView()));
    }

    public function commandeAction(Request $request) {
        $commande = new Commande();
        //CommandeStep1Type formule tous les attributs de l'objet commande sauf la collection de billet
        $form = $this->createForm(CommandeStep1Type::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //On récupère toutes les commandes à la date choisie par l'utilisateur
            $commandesDate = $this->getDoctrine()->getManager()
                                  ->getRepository('ylaakelBilleterieBundle:Commande')->commandesDate($commande->getLaDate());
            $allBillets = 0;
            //On compte le nombre de billets vendu à cette date
            foreach ($commandesDate as $uneCommande) {
                $allBillets += count($uneCommande->getInfoBillets());
            }
            //il y a + de 1000 billets vendus déjà, ou alors la personne souhaite commander + de 1000 billets
            if($allBillets > 10 || $commande->getNbrBillet() > 10) {
                $request->getSession()->getFlashBag()->add('notice', "Désolé il n'y a plus de place pour la date choisie.");
                return $this->render('ylaakelBilleterieBundle:Billeterie:commandeBillet.html.twig', array('form' => $form->createView()));
            }
            //génère un code
            $commande->setNumCommande(rand(1,10000) . 'aze' . rand(1, 10000) . 'rty');
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('ylaakel_billeterie_information_billet', array('numCommande' => $commande->getNumCommande()));      
        }

        return $this->render('ylaakelBilleterieBundle:Billeterie:commandeBillet.html.twig', array('form' => $form->createView()));
    }

    public function infoAction(Request $request, Commande $commande) {
        //On récupère le nombre de billet(s) que l'utilisateur a décidé de commander et on crée autant de formulaire
        $nbrBillet = $commande->getNbrBillet();
        for ($i=1; $i <= $nbrBillet; $i++) { 
            $infoBillet[$i] = new InfoBillet();
            //On ajoute chaque billet à la collection de la commande en cours
            $commande->addInfoBillet($infoBillet[$i]);
        }
        //CommandeStep2Type formule la partie collection de l'objet commande 
        $form = $this->createForm(CommandeStep2Type::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //On persist les billets remplis par l'utilisateur
            $em = $this->getDoctrine()->getManager();
            foreach ($commande->getInfoBillets() as $infoBillet) {
                $em->persist($infoBillet);
            }
            $em->flush();

            return $this->redirectToRoute('ylaakel_billeterie_paiement_billet', array('numCommande' => $commande->getNumCommande()));
        }

        return $this->render('ylaakelBilleterieBundle:Billeterie:informationBillet.html.twig', array('commande' => $commande, 'form' => $form->createView()));
    }


    public function paiementAction(Request $request, Commande $commande) {
        //api key pk_live_mEqbqgEwH5nndIdRrqC4Aodl
        $em = $this->getDoctrine()->getManager();
        //calcul du tarif ici
        $prixTotal = 0;
        $tabPrix = array();
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
            $em->flush();
        }

        return $this->render('ylaakelBilleterieBundle:Billeterie:paiementBillet.html.twig', array('commande' => $commande, 'prixTotal' => $prixTotal, 'allBillets' => $commande->getInfoBillets()));
    }

    public function confirmationAction(Request $request, Commande $commande) {
        $stripeToken = $request->get('stripeToken');

        //Envoi du mail final
        if(isset($stripeToken)) {
            $email = $commande->getEmail();
            $message = \Swift_Message::newInstance()
            ->setSubject("Confirmation d'achat")
            ->setFrom(array('green.tare@gmail.com' => 'Le Louvre'))
            ->setTo($email);
            $message->setBody($this->renderView('ylaakelBilleterieBundle:Billeterie:confirmationEmail.txt.twig', array('commande' => $commande, 'stripeToken' => $stripeToken, 'allBillets' => $commande->getInfoBillets())));            
            $this->get('mailer')->send($message);

            return $this->render('ylaakelBilleterieBundle:Billeterie:confirmationBillet.html.twig');
        }
    }
}

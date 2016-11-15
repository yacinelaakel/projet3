<?php

namespace ylaakel\BilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ylaakel\BilleterieBundle\Form\ContactType;
use ylaakel\BilleterieBundle\Entity\Contact;
use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Form\CommandeType;
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
        $form = $this->createForm(CommandeType::class, $commande);

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

    public function infoAction(Request $request, $numCommande) {
        $repository = $this->getDoctrine()->getManager()->getRepository('ylaakelBilleterieBundle:Commande');
        //Pas besoin de tester si l'objet' est null puisqu'il a été crée dans l'action précédente
        //On récupère l'objet avec son numéro de commande plutôt que son id
        $commande = $repository->findOneBy(array('numCommande' => $numCommande));
        //On récupère le nombre de billet(s) que l'utilisateur a décidé de commander et on crée autant de formulaire
        $nbrBillet = $commande->getNbrBillet();
        for ($i=1; $i <= $nbrBillet; $i++) { 
            $infoBillet[$i] = new InfoBillet();
            //On ajoute chaque billet à la collection de la commande en cours
            $commande->addInfoBillet($infoBillet[$i]);
        }
        $form = $this->createForm(CommandeType::class, $commande);
        //Il faut remettre les valeurs dans l'objet commande après la deuxième soumission de formulaire
        $tempDate = $commande->getLaDate();
        $tempType = $commande->getTypeBillet();
        $tempNbrBillet = $commande->getNbrBillet();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $commande->setLaDate($tempDate);
            $commande->setTypeBillet($tempType);
            $commande->setNbrBillet($tempNbrBillet);

            $em = $this->getDoctrine()->getManager();
            foreach ($commande->getInfoBillets() as $infoBillet) {
                $em->persist($infoBillet);
            }
            $em->flush();
            // return $this->redirectToRoute('ylaakel_billeterie_paiement_billet');
            return $this->redirectToRoute('ylaakel_billeterie_contact');

        }

        return $this->render('ylaakelBilleterieBundle:Billeterie:informationBillet.html.twig', array('commande' => $commande, 'form' => $form->createView()));
    }


    // public function paiementAction(Request $request, $numCommande) {
    //     $repository = $this->getDoctrine()->getManager()->getRepository('ylaakelBilleterieBundle:Commande');
    //     //Ici mon objet commande possède les informations sur la commande ET la collection des billets 
    //     $commande = $repository->findOneBy(array('numCommande' => $numCommande));
    // }
}

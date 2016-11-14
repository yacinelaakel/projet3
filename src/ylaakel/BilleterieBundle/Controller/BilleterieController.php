<?php

namespace ylaakel\BilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ylaakel\BilleterieBundle\Form\ContactType;
use ylaakel\BilleterieBundle\Entity\Contact;
use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Form\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
        //     // if(la date récupéré s'avère avoir + de 1000 ventes) {
        //     //     $request->getSession()->getFlashBag()->add('notice', 'Désolé il n'y a plus de place pour la date choisie.');
        //     //     return $this->render('ylaakelBilleterieBundle:Billeterie:commandeBillet.html.twig', array('form' => $form->createView()));
        //     // }
            //génère un code
            $commande->setNumCommande(rand(1,10000000) . 'aze' . rand(1, 10000000) . 'rty');
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('ylaakel_billeterie_information_billet', array('numCommande' => $commande->getNumCommande()));      
        }

        return $this->render('ylaakelBilleterieBundle:Billeterie:commandeBillet.html.twig', array('form' => $form->createView()));
    }

    public function infoAction(Request $request, $numCommande) {
        $repository = $this->getDoctrine()->getManager()->getRepository('ylaakelBilleterieBundle:Commande');
        //Pas besoin de tester si l'objet' est null puisqu'il a été crée dans l'action d'avant
        $commande = $repository->findOneBy(array('numCommande' => $numCommande));

        return $this->render('ylaakelBilleterieBundle:Billeterie:informationBillet.html.twig', array('commande' => $commande));
    }
}
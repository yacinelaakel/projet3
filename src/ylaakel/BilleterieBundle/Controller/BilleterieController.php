<?php

namespace ylaakel\BilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ylaakel\BilleterieBundle\Form\ContactType;
use ylaakel\BilleterieBundle\Entity\Contact;

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

    		$name = $form->get('name')->getData();
    		$email = $form->get('email')->getData();

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
}

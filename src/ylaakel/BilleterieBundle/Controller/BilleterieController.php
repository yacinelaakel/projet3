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
    		//Envoi du mail ici

      		$request->getSession()->getFlashBag()->add('notice', 'Message bien envoyé.');
      		return $this->redirectToRoute('ylaakel_billeterie_contact');
    	}	


    	return $this->render('ylaakelBilleterieBundle:Billeterie:contact.html.twig', array('form' => $form->createView()));
    }
}

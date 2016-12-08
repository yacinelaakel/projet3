<?php

namespace ylaakel\BilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ylaakel\BilleterieBundle\Entity\Commande;
use ylaakel\BilleterieBundle\Form\CommandeStep1Type;
use ylaakel\BilleterieBundle\Form\CommandeStep2Type;

class BilleterieController extends Controller
{

    public function commandeAction(Request $request) {
        $commande = new Commande();
        $em = $this->getDoctrine()->getManager();
        //CommandeStep1Type formule tous les attributs de l'objet commande sauf la collection de billet
        $form = $this->createForm(CommandeStep1Type::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $restrictionBillet = $this->get('ylaakel_billeterie.restriction.billet');
            if($restrictionBillet->nbrBilletIsTooMuch($commande)) {
                $request->getSession()->getFlashBag()->add('notice', "Désolé il n'y a plus de place pour la date choisie.");
                return $this->render('ylaakelBilleterieBundle:Billeterie:commandeBillet.html.twig', array('form' => $form->createView()));
            }
            //génère un code
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('ylaakel_billeterie_information_billet', array('numCommande' => $commande->getNumCommande()));      
        }

        return $this->render('ylaakelBilleterieBundle:Billeterie:commandeBillet.html.twig', array('form' => $form->createView()));
    }

    public function infoAction(Request $request, Commande $commande) {
        $commande = $commande->initBillets();
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
        $em = $this->getDoctrine()->getManager();

        $tarif = $this->get('ylaakel_billeterie.tarif');
        //calcul du tarif ici
        $commandeEtPrix = $tarif->calculTarif($commande);
        $commande->setPrixTotal($commandeEtPrix['prixTotal']);
        $em->flush();

        return $this->render('ylaakelBilleterieBundle:Billeterie:paiementBillet.html.twig', array('commande' => $commandeEtPrix['commande'], 'allBillets' => $commande->getInfoBillets(), 'prixTotal' => $commandeEtPrix['prixTotal']));
    }

    public function confirmationAction(Request $request, Commande $commande) {
        $em = $this->getDoctrine()->getManager();        
            $stripeToken = $request->get('stripeToken');
            if(isset($stripeToken)) {
                //La commande est maintenant payé
                $commande->setPaye(true);
                $commande->setNumPaiement($stripeToken);
                $em->flush();
                \Stripe\Stripe::setApiKey($this->getParameter('stripe_key'));
                try {
                    \Stripe\Charge::create(array(
                        "amount" => $commande->getPrixTotal()*100, 
                        "currency" => "eur",
                        "source" => $stripeToken,
                        "description" => "Charge OK"
                        ));
                } catch(\Stripe\Error\Card $e) {
                    $request->getSession()->getFlashBag()->add('notice', "Le paiement a échoué");
                    return $this->render('ylaakelBilleterieBundle:Billeterie:confirmationBillet.html.twig');                    
                }

                $email = $commande->getEmail();
                $message = \Swift_Message::newInstance()
                ->setSubject("Confirmation d'achat")
                ->setFrom(array($this->getParameter('mailer_user') => 'Le Louvre'))
                ->setTo($email);
                $image = $message->embed(\Swift_Image::fromPath('http://www.louvrebible.org/themes/louvrebible/img/logo_pyramide_accueil.png'));
                $message->setBody($this->renderView('ylaakelBilleterieBundle:Billeterie:confirmationEmail.html.twig', array('commande' => $commande, 'stripeToken' => $stripeToken, 'allBillets' => $commande->getInfoBillets(), 'image' => $image)));            
                $this->get('mailer')->send($message);

                return $this->render('ylaakelBilleterieBundle:Billeterie:confirmationBillet.html.twig');
            }
    }

}

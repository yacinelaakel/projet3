<?php 

namespace ylaakel\BilleterieBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AppController extends Controller {

    public function indexAction()
    {
        return $this->render('ylaakelBilleterieBundle:App:index.html.twig');
    }
}
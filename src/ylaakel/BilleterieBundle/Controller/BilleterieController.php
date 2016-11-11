<?php

namespace ylaakel\BilleterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BilleterieController extends Controller
{
    public function indexAction()
    {
        return $this->render('ylaakelBilleterieBundle:Billeterie:index.html.twig');
    }
}

<?php

namespace tests\ylaakelBilleterieBundle\InfoBillet;

use ylaakel\BilleterieBundle\Entity\InfoBillet;

class InfoBilletTest extends \PHPUnit_Framework_TestCase
{
    public function testNullPrix() {
        $infoBillet = new InfoBillet();
        $infoBillet->setPrix(null);
        $this->assertEquals(null, $infoBillet->getPrix());
    }
}
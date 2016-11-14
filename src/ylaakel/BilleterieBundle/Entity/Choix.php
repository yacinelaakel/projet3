<?php

namespace ylaakel\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Choix
 *
 * @ORM\Table(name="choix")
 * @ORM\Entity(repositoryClass="ylaakel\BilleterieBundle\Repository\ChoixRepository")
 */
class Choix
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="laDate", type="datetimetz")
     */
    private $laDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="typeBillet", type="boolean")
     */
    private $typeBillet;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrBillet", type="integer")
     */
    private $nbrBillet;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set laDate
     *
     * @param \DateTime $laDate
     *
     * @return Selection
     */
    public function setLaDate($laDate)
    {
        $this->laDate = $laDate;

        return $this;
    }

    /**
     * Get laDate
     *
     * @return \DateTime
     */
    public function getLaDate()
    {
        return $this->laDate;
    }

    /**
     * Set typeBillet
     *
     * @param boolean $typeBillet
     *
     * @return Selection
     */
    public function setTypeBillet($typeBillet)
    {
        $this->typeBillet = $typeBillet;

        return $this;
    }

    /**
     * Get typeBillet
     *
     * @return bool
     */
    public function getTypeBillet()
    {
        return $this->typeBillet;
    }

    /**
     * Set nbrBillet
     *
     * @param integer $nbrBillet
     *
     * @return Selection
     */
    public function setNbrBillet($nbrBillet)
    {
        $this->nbrBillet = $nbrBillet;

        return $this;
    }

    /**
     * Get nbrBillet
     *
     * @return int
     */
    public function getNbrBillet()
    {
        return $this->nbrBillet;
    }
}


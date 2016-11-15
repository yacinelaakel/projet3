<?php

namespace ylaakel\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Choix
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="ylaakel\BilleterieBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @ORM\OneToMany(targetEntity="ylaakel\BilleterieBundle\Entity\InfoBillet", mappedBy="commande")
     *
     */
    private $infoBillets;


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
     * @Assert\Type("bool")
     */
    private $typeBillet;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrBillet", type="integer")
     * @Assert\Range(min=1, minMessage="Il doit y avoir au moins {{ limit }} billet.", max=3, maxMessage="Il doit y avoir au plus {{ limit }} billets.", invalidMessage="Nombre invalide.")
     */
    private $nbrBillet;


    /**
     * @var string
     * 
     * @ORM\Column(name="numCommande", type="string", length=255)
     * @Assert\Length(min=3, max=15)
     */
    private $numCommande;

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

    /**
     * Set numCommande
     *
     * @param string $numCommande
     *
     * @return Commande
     */
    public function setNumCommande($numCommande)
    {
        $this->numCommande = $numCommande;

        return $this;
    }

    /**
     * Get numCommande
     *
     * @return string
     */
    public function getNumCommande()
    {
        return $this->numCommande;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->infoBillets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add infoBillet
     *
     * @param \ylaakel\BilleterieBundle\Entity\InfoBillet $infoBillet
     *
     * @return Commande
     */
    public function addInfoBillet(\ylaakel\BilleterieBundle\Entity\InfoBillet $infoBillet)
    {
        $this->infoBillets[] = $infoBillet;
        
        $infoBillet->setCommande($this);

        return $this;
    }

    /**
     * Remove InfoBillet
     *
     * @param \ylaakel\BilleterieBundle\Entity\InfoBillet $infoBillet
     */
    public function removeInfoBillet(\ylaakel\BilleterieBundle\Entity\InfoBillet $infoBillet)
    {
        $this->infoBillets->removeElement($infoBillet);
    }

    /**
     * Get infoBillets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfoBillets()
    {
        return $this->infoBillets;
    }
}

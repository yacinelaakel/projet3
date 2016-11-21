<?php

namespace ylaakel\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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
     * @Assert\Valid()
     */
    private $infoBillets;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="laDate", type="datetimetz")
     * @Assert\DateTime()
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
     * @Assert\Range(min=1, minMessage="Il doit y avoir au moins {{ limit }} billet.", max=1000, maxMessage="Il doit y avoir au plus {{ limit }} billets.", invalidMessage="Nombre invalide.")
     */
    private $nbrBillet;

    /**
     *
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Length(min=5, minMessage="L'e-mail doit faire au moins {{ limit }} caractères.", max=30, maxMessage="L'email doit faire moins de {{ limit }} caractères.")
     */
    private $email;


    /**
     * @var string
     * 
     * @ORM\Column(name="numCommande", type="string", length=255)
     * @Assert\Length(min=3)
     */
    private $numCommande;

    /**
     *
     * @ORM\Column(name="numPaiement", type="string", length=255, nullable=true)
     * @Assert\Length(min=3)
     */
    private $numPaiement;

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
        //On associe le billet à la commande
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

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Commande
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set numPaiement
     *
     * @param string $numPaiement
     *
     * @return Commande
     */
    public function setNumPaiement($numPaiement)
    {
        $this->numPaiement = $numPaiement;

        return $this;
    }

    /**
     * Get numPaiement
     *
     * @return string
     */
    public function getNumPaiement()
    {
        return $this->numPaiement;
    }

    /**
     * @Assert\Callback
     */
    public function isDateValid(ExecutionContextInterface $context)
    {
        $laDate = $this->getLaDate();
        $currentDate = date_create();
        $diff = date_diff($currentDate, $laDate)->format('%d');

        if ($diff < 0) {
            $context
                ->buildViolation('Invalide : jour interdit.')
                ->atPath('laDate')
                ->addViolation()
            ;
        }
        $dayLaDate = $laDate->format('l');
        if ($dayLaDate == 'Tuesday' || $dayLaDate == 'Sunday') {
            $context
                ->buildViolation('Invalide : jour interdit.')
                ->atPath('laDate')
                ->addViolation()
            ;
        }
    }

    /**
     * @Assert\Callback
     */
    public function isTypeBilletValid(ExecutionContextInterface $context)
    {
        $laDate = $this->getLaDate();
        $currentDate = date_create();
        $diff = date_diff($currentDate, $laDate)->format('%d');

        if ($diff == 0) {
            if($this->getTypeBillet()) {
                $context
                    ->buildViolation('Invalide : Après 14h seul le type "Demi-journée" est disponible.')
                    ->atPath('typeBillet')
                    ->addViolation()
                ;
            }
        }
    }
}


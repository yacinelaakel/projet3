<?php

namespace ylaakel\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use ylaakel\BilleterieBundle\Entity\InfoBillet;


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
     *
     * @ORM\Column(name="paye", type="boolean")
     * @Assert\Type("bool")
     */
    private $paye;

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
        $this->setNumCommande(rand(1,10000) . 'aze' . rand(1, 10000) . 'rty');
        $this->setPaye(false);
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

        //Si diff = 0 ça veut dire qu'il y a 0 jour de différence entre la date choisie et la date courante
        if ($diff == 0) {
            //On s'assure que ce soit vraiment le jour J en comparant le nom des jours (monday, tuesday...)
            if($laDate->format('l') == $currentDate->format('l')) {
                if($currentDate->format('H') >= 14) {
                    //Si l'utilisateur choisit un billet journée 
                    if($this->getTypeBillet()) {
                        $context
                            ->buildViolation("Passé 14h billet 'Journée' interdit.")
                            ->atPath('typeBillet')
                            ->addViolation()
                        ;
                    }
                }
            }
        }
    }

    //On récupère le nombre de billet(s) que l'utilisateur a décidé de commander et on crée autant de billets
    public function initBillets() { 
        $nbrBillet = $this->getNbrBillet();
        for ($i=1; $i <= $nbrBillet; $i++) { 
            $infoBillet[$i] = new InfoBillet();
            //On ajoute chaque billet à la collection de la commande en cours
            $this->addInfoBillet($infoBillet[$i]);
        }
        return $this;
    }

    /**
     * Set paye
     *
     * @param boolean $paye
     *
     * @return Commande
     */
    public function setPaye($paye)
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     * Get paye
     *
     * @return boolean
     */
    public function getPaye()
    {
        return $this->paye;
    }
}

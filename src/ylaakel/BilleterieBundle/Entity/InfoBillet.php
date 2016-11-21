<?php

namespace ylaakel\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * InfoBillet
 *
 * @ORM\Table(name="info_billet")
 * @ORM\Entity(repositoryClass="ylaakel\BilleterieBundle\Repository\InfoBilletRepository")
 */
class InfoBillet
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
     * @ORM\ManyToOne(targetEntity="ylaakel\BilleterieBundle\Entity\Commande", inversedBy="infoBillets")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $commande;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(min=3, minMessage="Le nom doit faire au moins {{ limit }} caractères.", max=15, maxMessage="Le nom doit faire moins de {{ limit }} caractères.")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\Length(min=3, minMessage="Le prénom doit faire au moins {{ limit }} caractères.", max=15, maxMessage="Le prénom doit faire moins de {{ limit }} caractères.")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     * @Assert\Type("string")
     */
    private $pays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetimetz")
     * @Assert\DateTime()
     */
    private $dateNaissance;

    /**
     * @ORM\Column(name="tarifReduit", type="boolean")
     * @Assert\Type("bool")
     */
    private $tarifReduit;

    /**
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     * @Assert\Range(min=0)
     */
    private $prix;

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
     * Set pays
     *
     * @param string $pays
     *
     * @return InfoBillet
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return InfoBillet
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return InfoBillet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return InfoBillet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set commande
     *
     * @param \ylaakel\BilleterieBundle\Entity\Commande $commande
     *
     * @return InfoBillet
     */
    public function setCommande(\ylaakel\BilleterieBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \ylaakel\BilleterieBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return InfoBillet
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return boolean
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return InfoBillet
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @Assert\Callback
     */
    public function isTypeBilletValid(ExecutionContextInterface $context)
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
    }
}

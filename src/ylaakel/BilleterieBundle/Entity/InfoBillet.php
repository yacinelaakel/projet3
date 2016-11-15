<?php

namespace ylaakel\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $commande;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetimetz")
     */
    private $dateNaissance;


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
}

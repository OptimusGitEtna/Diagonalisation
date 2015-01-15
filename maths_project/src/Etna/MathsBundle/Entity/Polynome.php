<?php

namespace Etna\MathsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Polynome
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Etna\MathsBundle\Entity\PolynomeRepository")
 */
class Polynome
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\OneToMany(targetEntity="Digit", mappedBy="polynome")
    * @ORM\JoinColumn(name="digit_id", referencedColumnName="id")
    */
    private $digits;

    /**
     * @var integer
     *
     * @ORM\Column(name="degre", type="integer")
     */
    private $degre;

    /**
     * @var integer
     *
     * @ORM\Column(name="resultat", type="integer", nullable=true)
     */
    private $resultat;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set degre
     *
     * @param integer $degre
     * @return Polynome
     */
    public function setDegre($degre)
    {
        $this->degre = $degre;

        return $this;
    }

    /**
     * Get degre
     *
     * @return integer 
     */
    public function getDegre()
    {
        return $this->degre;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Polynome
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

    public function __toString()
    {
        return $this->getNom();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->digits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add digits
     *
     * @param \Etna\MathsBundle\Entity\Digit $digits
     * @return Polynome
     */
    public function addDigit(\Etna\MathsBundle\Entity\Digit $digits)
    {
        $this->digits[] = $digits;

        return $this;
    }

    /**
     * Remove digits
     *
     * @param \Etna\MathsBundle\Entity\Digit $digits
     */
    public function removeDigit(\Etna\MathsBundle\Entity\Digit $digits)
    {
        $this->digits->removeElement($digits);
    }

    /**
     * Get digits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDigits()
    {
        return $this->digits;
    }

    /**
     * Set resultat
     *
     * @param integer $resultat
     * @return Polynome
     */
    public function setResultat($resultat)
    {
        $this->resultat = $resultat;

        return $this;
    }

    /**
     * Get resultat
     *
     * @return integer 
     */
    public function getResultat()
    {
        return $this->resultat;
    }
}

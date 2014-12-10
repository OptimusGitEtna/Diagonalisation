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
    * @ORM\OneToMany(targetEntity="Digit", mappedBy="$polynome")
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
}

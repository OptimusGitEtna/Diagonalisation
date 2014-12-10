<?php

namespace Etna\MathsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Digit
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Digit
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
    * @ORM\ManyToOne(targetEntity="Polynome", inversedBy="digits")
    * @ORM\JoinColumn(name="polynome_id", referencedColumnName="id")
    */
    private $polynome;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;


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
     * Set libelle
     *
     * @param string $libelle
     * @return Digit
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
}

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
     * @ORM\Column(name="value", type="integer")
     */
    private $value;


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
     * Set position
     *
     * @param integer $position
     * @return Digit
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return Digit
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set polynome
     *
     * @param \Etna\MathsBundle\Entity\Polynome $polynome
     * @return Digit
     */
    public function setPolynome(\Etna\MathsBundle\Entity\Polynome $polynome = null)
    {
        $this->polynome = $polynome;

        return $this;
    }

    /**
     * Get polynome
     *
     * @return \Etna\MathsBundle\Entity\Polynome 
     */
    public function getPolynome()
    {
        return $this->polynome;
    }

    public function __toString() {
        return (string)$this->value;
    }
}

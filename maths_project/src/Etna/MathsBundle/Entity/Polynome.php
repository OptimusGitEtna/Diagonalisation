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

    const DEGRE = 3;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\OneToMany(targetEntity="Digit", mappedBy="polynome", cascade={"all"})
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
     * @ORM\Column(name="resultat", type="string", length=20, nullable=true)
     */
    private $resultat;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="concat_form", type="string", length=255, nullable=true)
     */
    private $concatForm;


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
        $digits->setPolynome($this);
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
     * @param String $resultat
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
     * @return String
     */
    public function getResultat()
    {
        return $this->resultat;
    }

    /**
     * Set concatFormByCoefficients
     *
     * @param string $x3, $x2, $x1, $x0
     * @return String
     */
    public function setConcatFormByCoefficients()
    {
        $sPolynome = "";
        $iMax = count($this->getDigits())-1;
        foreach ($this->getDigits() as $iKey => $oDigit)
        {
            $iIndice = $iMax - $iKey;
            if (0 <= $oDigit->getValue())
            {
                if (3 != $iIndice)
                {
                    $sPolynome .= " + ";
                }
                $iCurrentValue = $oDigit->getValue();
            }
            else
            {
                $sPolynome .= " - ";
                $iCurrentValue = $oDigit->getValue() * (-1);
            }

            if (0 == $iIndice) {
                $sPolynome .= $iCurrentValue;
            }
            else {
                $sPolynome .= $iCurrentValue."x<sup>".$iIndice."</sup>";
            }
        }
        //var_dump("<PRE>",$sPolynome);die;
        $this->concatForm = $sPolynome;
        return $this;
    }

    /**
     * Set concatForm
     *
     * @param string $concatForm
     * @return Polynome
     */
    public function setConcatForm($concatForm)
    {
        $this->concatForm = $concatForm;
        return $this;
    }

    /**
     * Get concatForm
     *
     * @return string 
     */
    public function getConcatForm()
    {
        return $this->concatForm;
    }

    /**
     * Set concatFormByCoefficients
     *
     * @param string $x3, $x2, $x1, $x0
     * @return String
     */
    public function getConcatFormByCoefficients($oEm, $iId)
    {

        return $sPolynomeFactorised;
    }
}

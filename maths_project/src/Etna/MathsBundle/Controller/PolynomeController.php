<?php

namespace Etna\MathsBundle\Controller;

use Etna\MathsBundle\Entity\Digit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Etna\MathsBundle\Entity\Polynome;
use Etna\MathsBundle\Form\PolynomeType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Polynome controller.
 *
 * @Route("/polynome")
 */
class PolynomeController extends Controller
{
    const DISPLAY_RESULT_BY_DEFAULT = "Appuyer sur la touche \" = \"";
    const INTERVAL_MAX = 10;
    const INTERVAL_MIN = -10;
    const NO_RESULT = "Aucune racine";

    /**
     * Liste les polynomes et calcul les racines entieres de chacuns (Phase 1).
     *
     * @Route("/", name="polynome")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('EtnaMathsBundle:Polynome')->findAll();
        $aAllPolynomeResult = $this->calculPolynome($entities);

        // Itinialisation provisoire des resultats.
        foreach($entities as $entity)
        {
            $entity->setResultat(PolynomeController::DISPLAY_RESULT_BY_DEFAULT);
        }

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Liste les polynomes et calcul les racines entieres de chacuns (Phase 1).
     *
     * @Route("/polycaract", name="polynome_caracteristique")
     * @Method("GET")
     * @Template("EtnaMathsBundle:polynome_caracteristique:polynome_caract_index.html.twig")
     */
    public function indexPolynomeCaracteristiqueAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('EtnaMathsBundle:Polynome')->findAll();
        $aAllPolynomeResult = $this->calculPolynome($entities);

        // Itinialisation provisoire des resultats.
        foreach($entities as $entity)
        {
            $entity->setResultat(PolynomeController::DISPLAY_RESULT_BY_DEFAULT);
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Initialise une matrice carré de degré 3
     *
     * @Route("/initialisation/matrice", name="init_matrice")
     * @Method("POST")
     */
    public function initMatriceCarre3Ajax(Request $oResquest) {

        $aCoefficients = $oResquest->request->get('aCoefficient');
        if ($oResquest->isXmlHttpRequest()) {
            $iKey = 1;
            foreach ($aCoefficients as $sCoefficient) {
                // Creation des variables dynamiques a la volée (pattern "$a1,$a2,$b1,$b2,...").
                $scoeffName = 'a'.$iKey;
                $$scoeffName = $sCoefficient;
                $iKey++;
            }
            
            echo $a1." # ".$a3." # ".$a5;
        }
        $iResult = "fake";
        return new Response($iResult);
    }


    /**
     * Affiche la liste des polynomes et détermine leur forme factorisé.
     *
     * @Route("/factorisation/racine_evidentes", name="factorisation_polynome_index")
     * @Method("GET")
     * @Template()
     */
    public function factorisationIndexAction()
    {
        $oEm = $this->getDoctrine()->getManager();
        $aPolynome = $oEm->getRepository("EtnaMathsBundle:Polynome")->findAll();

        foreach ($aPolynome as $oPolynome)
        {
            $oPolynome->setConcatFormByCoefficients();
        }

        return array(
            'aPolynomes' => $aPolynome,
        );
    }

    /**
     * Calcul un polynome de degré 3
     *
     * @return array : Tableau des resultats du polynome.
     */
    private function calculPolynome($aPolynome)
    {
        $aPolynomeContent = array();
        if (isset($aPolynome)) {
        foreach ($aPolynome as $oPolynome)
        {
            $aPolynomeContent[] = $this->container->get('etna.mathsbundle')->convertObjectToArray($oPolynome);
        }

        return $aPolynomeContent;
        }
    }

    /**
     * Calcul les racines du polynome de degré 3 et retourne le resultat.
     *
     * @Route("/ajax/polynome/result/", name="show_polynome_result")
     * @Method("POST")
     */
    public function makePloynomeResultInAjax(Request $oResquest)
    {
        $iResult = 0;
        if ($oResquest->isXmlHttpRequest()) {

            $x3 = $oResquest->request->get('x3');
            $x2 = $oResquest->request->get('x2');
            $x1 = $oResquest->request->get('x1');
            $x0 = $oResquest->request->get('x0');

            $aAllEvidentRoots = $this->findEvidentRootByInterval($x3, $x2, $x1, $x0,
                                       PolynomeController::INTERVAL_MIN, PolynomeController::INTERVAL_MAX);

            $iResult = $aAllEvidentRoots['roots'];
        }
        return new Response($iResult);
    }

    /**
     * Ajoute la nouvelle ligne ajouté a la liste des polynomes.
     * Persiste les données en base et et retourne le script de la vue pour l'insertion dans la grille.
     *
     * @Route("/ajax/polynome/addraw/", name="add_polynome_raw")
     * @Method("POST")
     */
    public function addPolynomeRaw(Request $oResquest)
    {
        $iResult = 0;
        if ($oResquest->isXmlHttpRequest()) {

            $oEm = $this->getDoctrine()->getManager();


            // Recuperation des données de la requete ajax
            $polyname = $oResquest->request->get('polyname');
            $x3 = $oResquest->request->get('x3');
            $x2 = $oResquest->request->get('x2');
            $x1 = $oResquest->request->get('x1');
            $x0 = $oResquest->request->get('x0');
            $iResult = $x3+$x2+$x1+$x0;
            $iResult = $polyname."".$x3."".$x2."".$x1."".$x0;

            // Enregistrement des saisies en bases
            $oPolynome = new Polynome();
            $oPolynome->setDegre(Polynome::DEGRE);

            for ($i = 0; $i <= Polynome::DEGRE ; ++$i)
            {
               $oDigit = new Digit();
               $oDigit->setPosition($i);
               $test = "x$i";
               $oDigit->setValue($$test);
               $oPolynome->addDigit($oDigit);
            }

            $oPolynome->setNom($polyname);
            $oPolynome->setResultat($iResult);
            $oEm->persist($oPolynome);
            $oEm->flush();
        }
        return new Response($iResult);
    }

    /**
     * Ajoute la nouvelle ligne ajouté a la liste des polynomes.
     * Persiste les données en base et et retourne le script de la vue pour l'insertion dans la grille.
     *
     * @Route("/ajax/polynome/addraw/", name="delete_polynome_raw")
     * @Method("DELETE")
     */
    public function deletePolynomeRaw(Request $oResquest)
    {
        $iResult = 0;
        if ($oResquest->isXmlHttpRequest()) {

            $oEm = $this->getDoctrine()->getManager();
            // Recuperation des données de la requete ajax
            $polyname = $oResquest->request->get('polyname');
            $x3 = $oResquest->request->get('x3');
            $x2 = $oResquest->request->get('x2');
            $x1 = $oResquest->request->get('x1');
            $x0 = $oResquest->request->get('x0');

            $oPolynome = $oEm->getRepository("EtnaMathsBundle:Polynome")
                    ->findOneBy(array('nom' => $polyname));

            $oEm->remove($oPolynome);
            $oEm->flush();
        }
        return new Response();
    }

    /*
     * Retourne les racines évidentes dans un interval valeur définit en parametres.
     * Retourne false si le tableau est vide.
     */
    private function findEvidentRootByInterval($a, $b, $c, $d, $iMin, $iMax)
    {
        $aResultChecked = array();
        $sRoots = "";
        for ($i = $iMin, $nbPoly = 0; $i <= $iMax; ++$i)
        {
            $aResultChecked['displayResult'][$i]['isEvidentRoot'] = $this->findEvidenceRoot($a, $b, $c, $d, $i);
            if (true === $this->findEvidenceRoot($a, $b, $c, $d, $i))
            {
                $sRoots .= "[$i]";
                if ("" !== $i + 1) {
                    //$sRoots .= ", "; // TODO la virgule ne doit pas s'ajouter pour le dernier coeff.
                }
            }
            $sInterval = " { $iMin, $iMax }";
            $aResultChecked['roots'] = $sRoots;
        }

        return $aResultChecked;
    }

    /*
     * Methode retournant true si l'attribut $x est racine évidente du polynome. (isEvidentRoot)
     */
    private function findEvidenceRoot($a, $b, $c, $d, $x)
    {
        $aPolynome = array();
        $aResult = null;
        $aPolynome ['degre3'] = $a * pow($x, 3); // $a ** $b => fait pété la console symfony.
        $aPolynome ['degre2'] = $b * pow($x, 2);
        $aPolynome ['degre1'] = $c * pow($x, 1);
        $aPolynome ['degre0'] = $d;
        $iResult = $this->calculatingPolynome($aPolynome);
        $bResult = $this->isEvidentRoot($iResult);

        return $bResult;
    }

    /*
     * Calcul du polynome (additionne toute les parties du polynome).
     */
    private function calculatingPolynome($aPolynome)
    {
        $iResult = 0;
        foreach ($aPolynome as $sKey => $iCoefficient)
        {
            $iResult += $iCoefficient;
        }

        return $iResult;
    }

    /*
     * Renvoi true s'il s'agit d'une racine évidente
     */
    private function isEvidentRoot($iResult)
    {
        if (0 == $iResult)
            return true;
        return false;
    }

    /**
     * Détermine la forme factorisée du polynome
     *
     * @Route("/ajax/polynome/factorisation/", name="show_polynome_factor")
     * @Method("POST")
     */
    public function displayPolynomeFactorisedInAjax(Request $oResquest)
    {
        $iResult = 0;
        if ($oResquest->isXmlHttpRequest())
        {
            // racines entieres
            // Methode de calcul pour determiner les racines d'un polynome 2.
            $iId = $oResquest->request->get('iId');
            // TODO lbrau poursuivre le taf
            // Renvoi les racines evidentes du polynome passé en id.
            $aRoots = $this->findEvidentRootByIntervalById($iId, PolynomeController::INTERVAL_MIN, PolynomeController::INTERVAL_MAX);
            $sPolyFactSerialised = $this->defineFactoriseForm($aRoots, $iId);
        }

        return new Response($sPolyFactSerialised);
    }

    /*
     * Retourne les racines évidentes dans un interval valeur définit en parametres.
     * Retourne false si le tableau est vide.
     */
    private function findEvidentRootByIntervalById($iPolynomeId, $iMin, $iMax)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oPolynome = $oEm->getRepository("EtnaMathsBundle:Polynome")
            ->findOneBy(array('id' => $iPolynomeId));
        $aResultChecked = $this->findEvidentRootByIntervalByObject($oPolynome, $iMin, $iMax);

        return $aResultChecked;
    }

    private function findEvidentRootByIntervalByObject($oPolynome, $iMin, $iMax)
    {
        // Recuperation de tout les digits du polynomes.
        $aDigits = $oPolynome->getDigits();

        $i = 97; //Initialisation de la lettre 'a' en ascii.
        foreach ($aDigits as $oDigits) {

            $cCoeff = chr($i);
            $$cCoeff = $oDigits->getValue();
            $i++;
        }

        $iResult = $this->findEvidentRootByInterval($a, $b, $c, $d, $iMin, $iMax);
        $aAllRoots = explode(",",$iResult['roots']);
        preg_match_all("/\[(-?[0-9]+)?\]/", $iResult['roots'], $matches);
        $aAllRootsInt = array();
        foreach ($matches[1] as $iRoots) {

            $aAllRootsInt[] = (int)$iRoots;
        }

        return $aAllRootsInt;
    }

    /*
     * Détermine la forme factorisée du polynome
     */
    private function defineFactoriseForm($aRoots, $iIdPolynome)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oCurrentPolynome = $oEm->getRepository("EtnaMathsBundle:Polynome")
                                ->findOneBy(array("id" => $iIdPolynome));
        $sFormRender = "";
        foreach ($aRoots as $iRoots) {

        // Gestion du signe du coefficient.
        $signe = "-";
        if ($iRoots < 0) {
            $signe = "+";
            $iRoots = $iRoots * (-1);
        }
        $sFormRender .= "(x ".$signe." ".$iRoots.")";
    }

        if (count($aRoots) < 3 && count($aRoots) > 0)  {
            $sFormRender = $this->findQx($oCurrentPolynome, $aRoots);
        }
        elseif (count($aRoots) ==  0) {
            $sFormRender = PolynomeController::NO_RESULT;
        }

        switch (count($aRoots))
        {
            case 3:
                $nbRoots = 3;
                $retour = $nbRoots;
                break;
            case 2:
                $nbRoots = 2;
                $retour = $nbRoots;
                break;
            case 1:
                $nbRoots = 1;
                $retour = $nbRoots;
                break;
            case 0:
                // pas de forme
                $nbRoots = 0;
                $retour = 0;
                break;
            default:
                // racines ne correspond pas au polynome.
                $retour = count($aRoots);
        }

        // Gestion des pluriels
        $pl = ($nbRoots > 1 ? "s" : "");
        
       return $sFormRender." <br><i class=\"text-muted\">avec ".$nbRoots." racine$pl trouvée$pl</i>";
    }

    /*
     * Renvoi la forme factoriser Q(x) du polynome P(x)
     * Lorsqu'on recupere les digits du polynome, ceux ci ont des indexes inversés.
     * Exemple : aDigits[0] = a, aDigits[1] = b, ...
     */
    private function findQx($oPolynome, $aRoots)
    {
        // Initialisation dynamique des coefficient A,B,C et a,b,c.
        // Respectivement le polynome puissance 3 et puissance 2 (apres factorisation).
        $iAsciiIndex     = 65; // valeur A
        $iAsciiIndexmini = 97; // valeur a
        $iRacine = $this->getFirstPositiveRoots($aRoots);
        foreach ($oPolynome->getDigits() as $iKey => $oDigit) {
            $svarBigName   = chr($iAsciiIndex);
            $svarTinyName  = chr($iAsciiIndexmini);
            $$svarBigName  = $oDigit->getValue();
            
            if ($svarTinyName != 'a') {
                $$svarTinyName = $$svarBigName + $iTinyVarValuePrec * $iRacine;
            }
            else{
                $$svarTinyName = $$svarBigName;
            }

            $iAsciiIndex++;
            $iAsciiIndexmini++;
            $iTinyVarValuePrec = $$svarTinyName;
        }
        
        $sFirstFactor = "(x - $iRacine)";
        $sSecondFactor = "(".$a."x<sup>2</sup> + ".$b."x + $c)";
        
        // Gestion des cas ou il existe 2 racines entieres.
        if (count($aRoots) == 2) {
            
            $tab = array("$iRacine");
            $signe = " - ";
            $iRacineRest = array_diff($aRoots, $tab);
            if ($iRacineRest[0] < 0) {
                $iRacineRest[0] = $iRacineRest[0] * (-1);
                $signe = " + ";
            }
            
            $sSecondFactor = "(x $signe $iRacineRest[0])(".$b."x + $c)";
        }

        $sCompleteForm = $sFirstFactor.$sSecondFactor;
        
        return $sCompleteForm;
    }
    
    /*
     * Renvoi la premiere racine positive parmis toutes celles trouvées.
     */
    private function getFirstPositiveRoots($aRoots) {
        
        $iRender = $aRoots[0];
        foreach($aRoots as $iKey => $iRoot) {
            
            if ($iKey > 0 && $iRoot > 0) {
                
                $iRender = $iRoot;
                break;
            }
        }
        
        return $iRender;
    }

    /*
     * Renvoi les valeurs confirmant la regle suivante : d = x1 * x2 * x3
     * Necessite la validation de la deuxieme regle (findFactorisationFormWithB) pour etre accepté comme racine.
     */
    private function getFactorisationFormWithD($oPolynome, $aRoots) {
        // Recuperation du coefficient bx2 du polynome P(x)
        $sCoefficientContainer = "";
        $iOriginAscii = 97;
        foreach ($oPolynome->getDigits() as $oDigit) {
            $sCoefficientContainer  = chr($iOriginAscii);
            $iOriginAscii++;
        }

        // Factorisation avec uniquement une ou deux racines eniteres.
        $r1 = $aRoots[0];
        if (isset($aRoots[1])) {
            $r2 = $aRoots[1];
        }

        // TODO appel de methode pour déterminer la racine entiere du polynome Q(x).
        $sQx = $this->findQxWithRoots($oPolynome, $aRoots);
        //$r3 = $b - ($r1 + $r2);
    }
    
    /*
     * Renvoi le polynome sous la forme d'une chaine de caractere.
     * L'index du tableau sera egal au degré de puissance du polynome
     * exemple : ax2 + bx + c aura pour valeur $aQx[2] = a
     */
    private function PolynomeQxFormattingToString($aQx)
    {
        // TODO finir les cas de  gestions.
        // forme ax2 + bx + c
        // Données de tests
        //$aQx = array(3,-2,1); // tableau fournit en params.
        $sQx = "";
        $itaille = count($aQx);
        for ($iDegre = ($itaille -1); $iDegre >= 0; --$iDegre)
        {
            if ($aQx[$iDegre] == 0) {
                continue;
            }
            if ($iDegre == 1) {
                $sExposant = "x ";
            }
            else if ($iDegre == 0) {
                $sExposant = "";
            }
            else {
                $sExposant = "x<sup>".$iDegre."</sup> ";
            }
            // retire le signe + du premier element du polynome.
            $sSigne = "";
            if ($iDegre != $itaille-1) {
                $sSigne = " + ";
            }
            if ($aQx[$iDegre] < 0) {
                $sSigne = " - ";
                $aQx[$iDegre] = $aQx[$iDegre] * (-1);
            }
            
            $sQx .= $sSigne.$aQx[$iDegre].$sExposant;
        }
        
        return $sQx; 
    }

    /**
     * Ajoute la nouvelle ligne ajouté a la liste des polynomes.
     * Persiste les données en base et et retourne le script de la vue pour l'insertion dans la grille.
     *
     * @Route("/ajax/polynome/savedata/", name="save_data")
     * @Method("PUT")
     */
    public function savePolynomeRaw(Request $oResquest)
    {
        $iResult = 0;
        //var_dump($oResquest->request);die;
        if ($oResquest->isXmlHttpRequest()) {

            $oEm = $this->getDoctrine()->getManager();

            // Recuperation des données de la requete ajax
            $polyname = $oResquest->request->get('polyname');
            $x3 = $oResquest->request->get('x3');
            $x2 = $oResquest->request->get('x2');
            $x1 = $oResquest->request->get('x1');
            $x0 = $oResquest->request->get('x0');

            $oPolynome = $oEm->getRepository("EtnaMathsBundle:Polynome")
                ->findOneBy(array('nom' => $polyname));

            $oEm->remove($oPolynome);
            $oEm->flush();
        }
        return new Response();
    }
}
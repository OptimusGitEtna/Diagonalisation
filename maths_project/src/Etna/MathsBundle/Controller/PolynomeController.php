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
     * Creates a new Polynome entity.
     *
     * @Route("/", name="polynome_create")
     * @Method("POST")
     * @Template("EtnaMathsBundle:Polynome:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Polynome();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('polynome_show', array('id' => $entity->getId())));
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Polynome entity.
     *
     * @param Polynome $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Polynome $entity)
    {
        $form = $this->createForm(new PolynomeType(), $entity, array(
            'action' => $this->generateUrl('polynome_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Polynome entity.
     *
     * @Route("/new", name="polynome_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Polynome();
        $form   = $this->createCreateForm($entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Polynome entity.
     *
     * @Route("/{id}", name="polynome_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EtnaMathsBundle:Polynome')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Polynome entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Polynome entity.
     *
     * @Route("/{id}/edit", name="polynome_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EtnaMathsBundle:Polynome')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Polynome entity.');
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Polynome entity.
    *
    * @param Polynome $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Polynome $entity)
    {
        $form = $this->createForm(new PolynomeType(), $entity, array(
            'action' => $this->generateUrl('polynome_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }
    /**
     * Edits an existing Polynome entity.
     *
     * @Route("/{id}", name="polynome_update")
     * @Method("PUT")
     * @Template("EtnaMathsBundle:Polynome:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EtnaMathsBundle:Polynome')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Polynome entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('polynome_edit', array('id' => $id)));
        }
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Polynome entity.
     *
     * @Route("/{id}", name="polynome_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EtnaMathsBundle:Polynome')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Polynome entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('polynome'));
    }

    /**
     * Creates a form to delete a Polynome entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('polynome_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
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
                $sRoots .= "[$i] ";
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
     * Calcul du polynome
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
    {
        return true;
    }
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
            $aRoots = $this->findEvidentRootByIntervalById($iId, PolynomeController::INTERVAL_MIN, PolynomeController::INTERVAL_MAX);
            $sPolyFactSerialised = $this->defineFactoriseForm($aRoots);
        }

        return new Response("(X - x<sub>1</sub>)Q(x) ".$sPolyFactSerialised." racines trouvées" );
    }

    /*
     * Retourne les racines évidentes dans un interval valeur définit en parametres.
     * Retourne false si le tableau est vide.
     */
    private function findEvidentRootByIntervalById($iPolynomeId, $iMin, $iMax)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oPolynome = $oEm->getRepository("EtnaMathsBundle:Polynome")
            ->findBy(array('id' => $iPolynomeId));
        $this->findEvidentRootByIntervalByObject($oPolynome);
        return $aResultChecked;
    }

    private function findEvidentRootByIntervalByObject($oPolynome, $iMin, $iMax)
    {
        var_dump("coucou toi");
    }

    /*
     * Détermine la forme factorisée du polynome
     */
    private function defineFactoriseForm($aRoots)
    {
        $aRoots = explode(" ",$aRoots['roots']);
        // TODO les affectations de $retour sont la pour faire passer les tests avant edition de la methode.
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
                $retour = count($aRoots);;
        }

        // TODO ici appel de la methode pour serialisé la forme factorisé du polynome 3.

       return $retour;
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
        var_dump($oResquest->request);die;
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

<?php

namespace Etna\MathsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Etna\MathsBundle\Entity\Digit;
use Etna\MathsBundle\Form\DigitType;

/**
 * Digit controller.
 *
 * @Route("/digit")
 */
class DigitController extends Controller
{

    /**
     * Lists all Digit entities.
     *
     * @Route("/", name="digit")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EtnaMathsBundle:Digit')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Digit entity.
     *
     * @Route("/", name="digit_create")
     * @Method("POST")
     * @Template("EtnaMathsBundle:Digit:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Digit();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('digit_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Digit entity.
     *
     * @param Digit $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Digit $entity)
    {
        $form = $this->createForm(new DigitType(), $entity, array(
            'action' => $this->generateUrl('digit_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Digit entity.
     *
     * @Route("/new", name="digit_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Digit();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Digit entity.
     *
     * @Route("/{id}", name="digit_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtnaMathsBundle:Digit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Digit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Digit entity.
     *
     * @Route("/{id}/edit", name="digit_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtnaMathsBundle:Digit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Digit entity.');
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
    * Creates a form to edit a Digit entity.
    *
    * @param Digit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Digit $entity)
    {
        $form = $this->createForm(new DigitType(), $entity, array(
            'action' => $this->generateUrl('digit_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Digit entity.
     *
     * @Route("/{id}", name="digit_update")
     * @Method("PUT")
     * @Template("EtnaMathsBundle:Digit:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EtnaMathsBundle:Digit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Digit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('digit_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Digit entity.
     *
     * @Route("/{id}", name="digit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EtnaMathsBundle:Digit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Digit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('digit'));
    }

    /**
     * Creates a form to delete a Digit entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('digit_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

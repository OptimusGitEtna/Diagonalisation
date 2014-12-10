<?php

namespace Etna\MathsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Etna\MathsBundle\Entity\Polynome;
use Etna\MathsBundle\Form\PolynomeType;

/**
 * Polynome controller.
 *
 * @Route("/polynome")
 */
class PolynomeController extends Controller
{

    /**
     * Lists all Polynome entities.
     *
     * @Route("/", name="polynome")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EtnaMathsBundle:Polynome')->findAll();

        return array(
            'entities' => $entities,
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
}

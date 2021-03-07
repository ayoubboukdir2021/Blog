<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @Route("admin/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="admin_category_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('BlogBundle:Category')
                         ->CreateQueryBuilder("p")
                         ->OrderBy('p.id',"DESC")
                         ->getQuery()->getResult();


        $paginator = $this->get('knp_paginator');
        $paginatorPost = $paginator->paginate(
            $categories,
            $request->query->getInt('page' , 1),
            5
        );

        return $this->render('category/index.html.twig', array(
            'categories' => $paginatorPost,
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="admin_category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('BlogBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $category->setCreateAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());

            $em->persist($category);
            $em->flush();

            $this->addFlash('success' , 'La nouvelle categorie ' .  $category->getLibelle() . ' est ajoutée avec succès');

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="admin_category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}/remove", name="admin_category_remove")
     * @Method("GET")
     */
    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->CreateQuery('delete from BlogBundle:Category c where c.id IN (:id)');
        $query->setParameter("id",$id);
        $query->execute();

        $this->addFlash('success' , 'Le categorie ' . $category->getLibelle() . ' a été supprimer avec succès');

        return $this->redirectToRoute('admin_category_index');
    }


    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="admin_category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('BlogBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success' , 'Le categorie ' . $category->getLibelle() . ' a été modifier avec succès');

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{id}", name="admin_category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('admin_category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

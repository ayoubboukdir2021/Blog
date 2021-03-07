<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Post;
use BlogBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Post controller.
 *
 * @Route("user")
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/{id}/articles", name="user_articles_index")
     * @Method("GET")
     */
    public function indexAction(Request $request , $id )
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('BlogBundle:Post')->findBy(['user'=>$id]);

        $paginator = $this->get('knp_paginator');
        $paginatorPost = $paginator->paginate(
            $posts,
            $request->query->getInt('page' , 1),
            5
        );

        return $this->render('post/index.html.twig', array(
            'posts' => $paginatorPost
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/{id}/article/new", name="user_articles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request , $id)
    {
        $post = new Post();
        $form = $this->createForm('BlogBundle\Form\PostType', $post , ["validation_groups" => ["new"]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em = $this->getDoctrine()->getManager();
            $file = $post->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_post_directory'),$fileName);
            $post->setImage($fileName);

            $post->setUser($id);

            $em->persist($post);
            $em->flush();
            $this->addFlash('success' , 'La nouvelle post ' . $post->getTitre() . ' est ajoutée avec succès');
            return $this->redirectToRoute('user_articles_index', array('id' => $id));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("use/{iduser}/article/{id}", name="user_articles_show")
     * @Method("GET")
     */
    public function showAction(Post $post , $id)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("user/{iduser}/article/{id}/edit", name="user_articles_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post ,$iduser)
    {
        $deleteForm = $this->createDeleteForm($post);
        $oldPost = $post->getImage();
        $editForm = $this->createForm('BlogBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) 
        {
            if($post->getImage() == null)
            {
                $post->setImage($oldPost);
            }
            else
            {
                $file = $post->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('uploads_post_directory'),$fileName);
                $post->setImage($fileName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_articles_index', array('id' => $iduser));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("user/article/{id}", name="user_articles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {   
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        

        $this->addFlash('success' , 'La post ' . $post->getTitre() . ' a été supprimé avec succès');
        return $this->redirectToRoute('user_articles_index' , array('id'=>$post->getUser()));
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_articles_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

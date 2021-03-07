<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use BlogBundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAll();

        $paginator = $this->get('knp_paginator');
        $paginatorPost = $paginator->paginate(
            $posts,
            $request->query->getInt('page' , 1),
            5
        );

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', ['posts'=> $paginatorPost]);
    }

    /**
     * @Route("/show/{id}", name="showpage")
     */
    public function showAction(Request $request , $id)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findBy(['id'=>$id]);

        // replace this example code with whatever you need
        return $this->render('default/show.html.twig', ['posts'=> $posts]);
    }

    /**
     * @Route("/contact", name="contactpage")
     */
    public function contactAction(Request $request , $id)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findBy(['id'=>$id]);

        // replace this example code with whatever you need
        return $this->render('default/contact.html.twig', ['posts'=> $posts]);
    }

    // /**
    //  * @Route("/password", name="passwordpage")
    //  */
    // public function changepasswordAction(Request $request , $id)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $posts = $em->getRepository('BlogBundle:Post')->findBy(['id'=>$id]);

    //     // replace this example code with whatever you need
    //     return $this->render('default/password.html.twig', ['posts'=> $posts]);
    // }
}

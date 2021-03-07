<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\Request;
//use FOS\UserBundle\Model\User;
//use AppBundle\Entity\User;


class DefaultController extends Controller
{
    /**
     * @Route("/admin/" , name="admin_index_user")
     */
    public function indexAction(request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$users = $em->getRepository('AppBundle:User')->CreateQueryBuilder("p")
    												 ->OrderBy('p.id',"DESC")
    												 ->getQuery()->getResult();


        $paginator = $this->get('knp_paginator');
        $paginatorPost = $paginator->paginate(
            $users,
            $request->query->getInt('page' , 1),
            10
        );

        return $this->render('@Admin/Default/index.html.twig', ['users'=>$paginatorPost]);
    }

    /**
     * @Route("/admin/role/{id}" , name="admin_role_user")
     */
    public function roleAction(request $request , $id)
    {
       // Retrieve entity manager of doctrine
        $em = $this->getDoctrine()->getManager();

        // Search for the UserEntity, retrieve the repository
        $userRepository = $em->getRepository("AppBundle:User");
        // or $userRepository = $em->getRepository("myBundle:User");

        $user = $userRepository->findOneBy(["id" => $id]);
        
        // Add the role that you want !
        $user->addRole("ROLE_ADMIN");

        // Save changes in the database
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_index_user');
    }

    /**
     * @Route("/admin/user/{id}/remove" , name="admin_remove_user")
     */
    public function removeUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->CreateQuery('delete from AppBundle:User c where c.id IN (:id)');
        $query->setParameter("id",$id);
        $query->execute();

        return $this->redirectToRoute('admin_index_user');
    }

    /**
     * @Route("/admin/user/suspend/{id}" , name="admin_suspend_user")
     */
    public function suspendAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->CreateQuery('update AppBundle:User c set c.enabled = false where c.id IN (:id)');
        $query->setParameter("id",$id);
        $query->execute();

        return $this->redirectToRoute('admin_index_user');
    }

     /**
     * @Route("/admin/user/desactiver" , name="admin_desactiver_user")
     */
    public function desactiverAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
    	$query = $em->CreateQueryBuilder("p")
					->where('p.enabled = :etat')
					->OrderBy('p.id',"DESC")
					->setParameters(['etat'=>false])
					->getQuery();

		$users = $query->getResult();
    				


        $paginator = $this->get('knp_paginator');
        $paginatorPost = $paginator->paginate(
            $users,
            $request->query->getInt('page' , 1),
            10
        );

        return $this->render('@Admin/Default/index.html.twig', ['users'=>$paginatorPost]);
    }

     /**
     * @Route("/admin/user/activer" , name="admin_activer_user")
     */
    public function activerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    	$users = $em->getRepository('AppBundle:User')->CreateQueryBuilder("p")
    												 ->where('p.enabled = :etat')
    												 ->OrderBy('p.id',"DESC")
    												 ->setParameters(['etat'=>true])
    												 ->getQuery()->getResult();


        $paginator = $this->get('knp_paginator');
        $paginatorPost = $paginator->paginate(
            $users,
            $request->query->getInt('page' , 1),
            10
        );

        return $this->render('@Admin/Default/index.html.twig', ['users'=>$paginatorPost]);
    }
}

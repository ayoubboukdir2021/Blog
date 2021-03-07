<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user/dashboard" , name="user_dashboard")
     */
    public function indexAction()
    {
        return $this->render('@Blog/Default/index.html.twig');
    }
}

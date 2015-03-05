<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="project_index")
     */
    public function indexAction()
    {
        return $this->render('project/index.html.twig');
    }

    /**
     * @Route("/project/{id}", name="project_show")
     */
    public function showAction($id)
    {
        return $this->render('project/show.html.twig', array(
            'id' => $id,
        ));
    }
}

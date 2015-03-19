<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

use AppBundle\Entity\Project;

class ProjectController extends Controller
{
    /**
     * @Route("/project", name="project_index")
     */
    public function indexAction()
    {
        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Project')
            ->findAll()
        ;

        return $this->render('project/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @Route("/project/new", name="project_new")
     */
    public function newAction(Request $request)
    {
        if (!$this->isGranted('ROLE_USER'))
        {
            $this->addFlash('warning', 'Merci de vous identifier pour déposer votre projet.');

            return $this->redirectToRoute('_login');
        }

        $entity = new Project;
        $entity->setContribMaxDate(new \DateTime());
        $entity->setFundColl(0);
        $entity->setOwner($this->getUser());

        $form = $this->createNewForm($entity);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $this->getDoctrine()->getEntityManager()->persist($entity);
            $this->getDoctrine()->getEntityManager()->flush();

            $this->addFlash('success', 'Votre projet a bien été créé.');

            return $this->redirectToRoute('project_show', array(
                'id' => $entity->getId(),
            ));
        }

        return $this->render('project/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/project/{id}", name="project_show")
     */
    public function showAction($id)
    {
        $entity = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Project')
            ->find($id)
        ;

        return $this->render('project/show.html.twig', array(
            'entity' => $entity,
        ));
    }

    private function createNewForm(Project $entity)
    {
        return $this->createFormBuilder($entity)
            ->add('title', 'text', array(
                'label'       => 'Titre',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('description', 'textarea', array(
                'label'       => 'Description',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('cover', 'file', array(
                'label' => 'Couverture',
            ))
            ->add('fundObj', 'money', array(
                'label'       => 'Objectif de fincancement',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('contribMinAmount', 'money', array(
                'label'       => 'Montant minimum de participation',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('contribMaxDate', 'date', array(
                'label'       => 'Date limite de participation',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
                'format'      => 'dd/MM/yyyy',
            ))
            ->add('submit', 'submit', array(
                'label' => 'Valider',
            ))
            ->getForm()
        ;
    }
}










<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

use AppBundle\Entity\Project;
use AppBundle\Entity\Contrib;

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

        $now = new \DateTime;
        $nextMonth = $now->add(new \DateInterval('P1M'));

        $entity = new Project;
        $entity->setContribMaxDate($nextMonth);
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

        $contribForm = $this->createContribForm($entity);

        return $this->render('project/show.html.twig', array(
            'entity'      => $entity,
            'contribForm' => $contribForm->createView(),
        ));
    }

    /**
     * @Route("/project/{id}/contrib", name="project_contrib")
     */
    public function contribAction(Request $request, Project $project)
    {
        if (!$this->isGranted('ROLE_USER'))
        {
            $this->addFlash('warning', 'Merci de vous identifier pour contribuer à ce projet.');

            return $this->redirectToRoute('login');
        }

        $form = $this->createContribForm($project);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $amount = $form->get('amount')->getData();

            $contrib = new Contrib;
            $contrib->setAmount($amount);
            $contrib->setUser($this->getUser());
            $contrib->setProject($project);
            $contrib->setCreatedAt(new \DateTime);

            $project->setFundColl(
                $project->getFundColl() + $amount
            );

            $this->getDoctrine()->getManager()->persist($contrib);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre participation de '.$this->get('app.twig.app_extension')->currency($amount).' a bien été enregistrée.');
        }
        else {
            $this->addFlash('danger', (string) $form->getErrors());
        }

        return $this->redirectToRoute('project_show', array(
            'id' => $project->getId(),
        ));
    }

    private function createNewForm(Project $entity)
    {
        return $this
            ->createFormBuilder($entity)
            ->add('title', 'text', array(
                'label'       => 'Titre',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('subtitle', 'text', array(
                'label' => 'Sous-titre',
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

    private function createContribForm(Project $entity)
    {
        $data = array('amount' => $entity->getContribMinAmount());

        return $this
            ->createFormBuilder($data)
            ->add('amount', 'text', array(
                'label'          => false,
                'error_bubbling' => true,
                'constraints'    => array(
                    new NotBlank(array('message' => 'Ce champs est obligatoire')),
                    new Range(array(
                        'min' => $entity->getContribMinAmount(),
                        'minMessage' => 'Montant minimum de {{ limit }} €',
                        'invalidMessage' => 'Montant incorrect',
                    )),
                ),
            ))
            ->getForm()
        ;
    }
}

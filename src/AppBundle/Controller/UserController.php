<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use AppBundle\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/user/new", name="user_new")
     */
    public function newAction(Request $request)
    {
        $user = new User;

        $form = $this->createFormBuilder($user)
            ->add('username', 'text', array(
                'label'       => 'Identifiant',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('password', 'password', array(
                'label'       => 'Mot de passe',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('email', 'email', array(
                'label' => 'Adresse email',
                'constraints' => array(
                    new NotBlank(array('message' => 'Ce champs est obligatoire')),
                    new Email(array('message' => 'Cet email est incorrect')),
                )
            ))
            ->add('submit', 'submit', array(
                'label' => 'Valider',
            ))
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $this->getDoctrine()->getEntityManager()->persist($user);
            $this->getDoctrine()->getEntityManager()->flush();

            $this->addFlash('success', 'Votre compte a bien été créé.');
            
            return $this->redirectToRoute('_login');
        }

        return $this->render('user/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

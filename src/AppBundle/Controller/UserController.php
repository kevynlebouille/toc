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

        $form = $this->createNewForm($user);

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

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createNewForm($user);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $this->getDoctrine()->getEntityManager()->persist($user);
            $this->getDoctrine()->getEntityManager()->flush();

            $this->addFlash('success', 'Votre compte a bien été mis à jour.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function createNewForm(User $user)
    {
        if ($user->getId())
        {
            $passwordType    = 'repeated';
            $passwordOptions = array(
                'label'           => 'Mot de passe',
                'type'            => 'password',
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'error_bubbling'  => true,
            );
        }
        else
        {
            $passwordType    = 'password';
            $passwordOptions = array(
                'label'       => 'Mot de passe',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            );
        }

        return $this->createFormBuilder($user)
            ->add('username', 'text', array(
                'label'       => 'Identifiant',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('password', $passwordType, $passwordOptions)
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
    }
}

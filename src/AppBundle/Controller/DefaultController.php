<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $kevyn = $this
            ->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find(1)
        ;

        dump($kevyn);

        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $defaultData = array();

        $form = $this->createFormBuilder($defaultData)
            ->add('name', 'text', array(
                'label'       => 'Nom',
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('email', 'email', array(
                'label' => 'Adresse email',
                'constraints' => array(
                    new NotBlank(array('message' => 'Ce champs est obligatoire')),
                    new Email(array('message' => 'Cet email est incorrect')),
                )
            ))
            ->add('message', 'textarea', array(
                'constraints' => new NotBlank(array('message' => 'Ce champs est obligatoire')),
            ))
            ->add('submit', 'submit', array(
                'label' => 'Envoyer',
            ))
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $data = $form->getData();

            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                ->setSubject('[TOC] Formulaire de contact')
                ->setFrom('no-reply@example.com')
                ->setTo('kevyn@opsone.net')
                ->setBody(
                    $this->renderView('mailer/contact.html.twig', $data),
                    'text/html'
                )
                /*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView('mailer/contact.txt.twig', $data),
                    'text/plain'
                )
                */
            ;
            
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé.');
            
            return $this->redirectToRoute('contact');
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig');
    }
}

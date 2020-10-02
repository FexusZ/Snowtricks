<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use \App\Form\RegistrationFormType;
use \App\Form\ClientType;
use \App\Entity\Client as ClientEntity;

class ClientController extends AbstractController
{
    /**
     * @Route("/login", name="app.login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('error', 'déjà connecté!');
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['current_menu' => 'app.login', 'last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/profile/{id<[0-9]+>}", name="app.profile")
     */
    public function profile(ClientEntity $user, Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Modification effectué!');
        }
        return $this->render('registration/profile.html.twig', [
            'form' => $form->createView(), 'current_menu' => 'app.profile'
        ]);
    }

    /**
     * @Route("/logout", name="app.logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

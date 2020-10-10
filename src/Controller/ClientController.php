<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use \App\Form\RegistrationType;
use \App\Form\ClientType;
use \App\Form\ForgotPasswordType;
use \App\Form\ResetPasswordType;
use \App\Entity\Client as ClientEntity;
use \App\Repository\ClientRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class ClientController extends AbstractController
{
    /**
     * @Route("/login", name="app.login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('danger', 'déjà connecté!');
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['current_menu' => 'app.login', 'last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/profil", name="app.profil")
     */
    public function profile(Request $request): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Pas connecté!');
            return $this->redirectToRoute('index');
        }
        $user = $this->getUser();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload_file = $this->getParameter('upload_directory');

            $file = $request->files->get('registration')['profile_picture'];
            if ($file) {
                $file_name = md5(uniqid()). '.'.$file->guessExtension();
                $file->move($upload_file, $file_name);
                $user->setImage($file_name);
            }
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Modification effectué!');
            return $this->redirectToRoute('app.profil', ['id' => $user->getId()]);
        }
        return $this->render('registration/profile.html.twig', [
            'form' => $form->createView(), 'current_menu' => 'app.profil'
        ]);
    }

    /**
     * @Route("/logout", name="app.logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/registration", name="app.register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        if ($this->getUser()) {
            $this->addFlash('danger', 'déjà connecté!');
            return $this->redirectToRoute('index');
        }

        $user = new ClientEntity();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setActivationToken(md5(uniqid()));
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $upload_file = $this->getParameter('upload_directory');
            $file = $request->files->get('registration')['profile_picture'];
            if ($file) {
                $file_name = md5(uniqid()). '.'.$file->guessExtension();
                $file->move($upload_file, $file_name);
                $user->setImage($file_name);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new Email())
            ->from('fexus.j.sebastien@gmail.com')
            ->to($user->getEmail())
            ->subject('Confirmez votre compte')
            ->html('Valider mon compte : <a href="127.0.0.1:8000/verify/email/' . $user->getActivationToken() . '">ICI</a>');

            $mailer->send($email);

            $this->addFlash('success', 'Un email vous a été envoyé, validez le avant de vous connecter!');
            return $this->redirectToRoute('index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(), 'current_menu' => 'app.register'
        ]);
    }

    /**
     * @Route("/verify/email/{token}", name="app.check_account")
     */
    public function check_account($token, ClientRepository $client): Response
    {
        if ($this->getUser()) {
            $this->addFlash('error', 'déjà connecté!');
            return $this->redirectToRoute('index');
        }

        $user = $client->findOneBy(['activation_token' => $token]);

        if ($user->getActivationToken() !== null) {
            $this->addFlash('success', 'Compte validé.');
            $user->setActivationToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        } else {
            $this->addFlash('danger', 'Le token est inexistant, ou déjà utilisé.');
        }

        return $this->redirectToRoute('app.login');
    }

    /**
     * @Route("/forgot_password", name="app.forgot_password")
     */
    public function forgot_password(Request $request, MailerInterface $mailer, ClientRepository $client)
    {
        if ($this->getUser()) {
            $this->addFlash('danger', 'déjà connecté!');
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user =  $user = $client->findOneBy(['email' => $form->get('email')->getData()]);

            if (!$user) {
                $this->addFlash('danger', 'Aucun compte relié a l\'email : ' .$form->get('email')->getData());
            } elseif($user->getActivationToken() !== null) {
                $this->addFlash('danger', 'Compte non validé.');
            } else {
                $user->setResetToken(md5(uniqid()));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $url = $this->generateUrl('app.reset_password', ['token' => $user->getResetToken()], UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new Email())
                ->from('fexus.j.sebastien@gmail.com')
                ->to($user->getEmail())
                ->subject('Modification du mot de passe')
                ->html('Modifier mon mot de passe : <a href="' . $url . '">ICI</a>');
                $mailer->send($email);

                $this->addFlash('success', 'Un email vous a été envoyé');

                return $this->redirectToRoute('app.login');
            }
        }

        return $this->render('registration/forgot_password.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    /**
     * @Route("/reset_password/{token}", name="app.reset_password")
     */
    public function reset_password($token, ClientRepository $client, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser()) {
            $this->addFlash('danger', 'déjà connecté!');
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        $user = $client->findOneBy(['reset_token' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'Aucune demande de réinitialisation enregistré lié a ce compte, ou réinitialisation déjà utilisé.');
            return $this->redirectToRoute('app.login');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data['password'] === $data['confirm_password']) {
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $data['password']
                ));

                $user->setResetToken(null);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Mot de passe validé.');
                return $this->redirectToRoute('app.login');
            } else {
                $this->addFlash('danger', 'Le mot de passe et la confirmation ne sont pas les mêmes');
            }
        }

        return $this->render('registration/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
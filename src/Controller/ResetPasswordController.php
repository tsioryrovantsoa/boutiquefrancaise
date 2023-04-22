<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mot-de-passe-oublier", name="app_reset_password")
     */
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                // 1 : Enregistrer en base les demandes de mot de passe oubliee
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
                // 2- Envoyer mail avec liens pour mettre a jour

                $url = $this->generateUrl('update_password', ['token' => $reset_password->getToken()]);

                $mail = new Mail();
                $content = 'Bonjour ' . $user->getFirstname() . '<br/> Vous avez demander a reinitialiser votre mot de passe sur la boutiquefrancaise <br/><br/>';
                $content .= 'Merci de cliquer sur le liens suivantes pour <a href=' . $url . '>Cliquer ici</a>';
                $mail->send($user->getEmail(), $user->getFirstname() . '' . $user->getLastname(), 'Reinitialiser votre mot de passe sur la Boutique francaise', $content);
                $this->addFlash('notice', 'Vous allez recevoir un mail pour reinitialiser le mot de passe');
            } else {
                $this->addFlash('notice', 'Cette adresse e-mail est inconnu');
            }
        }


        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function update(Request $request, $token, UserPasswordEncoderInterface $encoder)
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$reset_password) {
            return $this->redirectToRoute('app_reset_password');
        }

        $now = new DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            //modifier mon mot de passe
            $this->addFlash('notice', 'Votre demande de mot de passe a expirer. Renouveler');
            return $this->redirectToRoute('app_reset_password');
        }


        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_password = $form->get('new_password')->getData();
            $password = $encoder->encodePassword($reset_password->getUser(), $new_password);

            $reset_password->getUser()->setPassword($password);
            // $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien ete mise a jour');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

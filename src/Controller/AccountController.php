<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig',);
    }

    /**
     * @Route("/compte/motdepasse", name="app_account_password")
     */
    public function forgetpasswd(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;
        $user = $this->getUser();
        // dd($user);
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_password)) {
                $new_password = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_password);

                $user->setPassword($password);
                // $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = 'Votre mot de passe est mis a jour';
            } else {
                $notification = 'Votre mot de passe actuel nest pas le bon';
            }
        }


        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/compte/addresse", name="account_address")
     */
    public function address(): Response
    {
        // dd($this->getUser());
        return $this->render('account/address.html.twig',);
    }

    /**
     * @Route("/compte/addresse/add", name="add_account_address")
     */
    public function createaddress(Cart $cart, Request $request): Response
    {
        // dd($this->getUser());
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            // dd($address);
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {

                return $this->redirectToRoute('account_address');
            }
        }

        return $this->render('account/addressform.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/addresse/edit/{id}", name="edit_account_address")
     */
    public function editaddress(Request $request, $id): Response
    {
        // dd($this->getUser());
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            // dd($address);
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/addressform.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/addresse/delete/{id}", name="delete_account_address")
     */
    public function delete($id): Response
    {
        // dd($this->getUser());
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address || $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }


        return $this->redirectToRoute('account_address');
    }
}

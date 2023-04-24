<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="app_order_validate")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        //dd($order);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Modifier status commande
        if ($order->getState() == 0) {
            //Vider le panier
            $cart->remove();

            $order->setState(1);
            $this->entityManager->flush();
            // Send Mail

            $mail = new Mail();
            $content = "<h1>Bonjour " . $order->getUser()->getFirstname() . "</h1><br/> Merci pour votre commande.<br/> A Bientot !";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande ' . $order->getReference() . ' sur La Boutique Francaise est bien validée', $content);
        }


        //Afficher les informations de la commande de l'utilisateur

        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}

<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mon-panier", name="app_cart")
     */
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id): Response
    {
        // dd($id);
        $cart->add($id);
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function remove(Cart $cart): Response
    {
        // dd($id);
        $cart->remove();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_cart")
     */
    public function delete(Cart $cart, $id): Response
    {
        // dd($id);
        $cart->delete($id);
        return $this->redirectToRoute('app_cart');
    }


    /**
     * @Route("/cart/decrease/{id}", name="decrease_cart")
     */
    public function decrease(Cart $cart, $id): Response
    {
        // dd($id);
        $cart->decrease($id);
        return $this->redirectToRoute('app_cart');
    }
}

<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $cart = $this->get();

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function getFull()
    {
        $cartComplete =  [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $productobject = $this->entityManager->getRepository(Product::class)->findOneById($id);
                if (!$productobject) {
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $productobject,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }
    public function delete($id)
    {
        $cart = $this->get();

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }
    public function decrease($id)
    {
        $cart = $this->get();

        if ($cart[$id] > 1) {
            $cart[$id]--;
            return $this->session->set('cart', $cart);
        } else {
            return $this->delete($id);
        }
    }
}

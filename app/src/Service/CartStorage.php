<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartStorage
{
    /**
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @return Order|null
     */
    public function getCart(): ?Order
    {
        $cart = $this->getSession()->get('cart');

        if (!$cart) {
            $cart = new Order();
        }

        return $cart;
    }

    /**
     * @param Order $cart
     */
    public function setCart(Order $cart): void
    {
        $this->getSession()->set('cart', $cart);
    }

    /**
     * @throws Exception
     */
    public function save(Order $cart): void
    {
        try {
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            Throw new Exception('Error while saving order');
        }

    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}

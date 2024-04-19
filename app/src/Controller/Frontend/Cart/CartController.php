<?php

namespace App\Controller\Frontend\Cart;

use App\Form\CartType;
use App\Service\CartStorage;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index(CartStorage $cartStorage, Request $request): Response
    {
        $cart = $cartStorage->getCart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new DateTime());
            $cartStorage->save($cart);

            return $this->redirectToRoute('cart_show');
        }

        return $this->render('frontend/cart/cart.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }
}

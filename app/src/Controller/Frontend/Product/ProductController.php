<?php

namespace App\Controller\Frontend\Product;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Service\CartStorage;
use DateTime;
use Exception;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function show(
        #[MapEntity(expr: 'repository.findOneByKey(key)')]
        Product $product,
        Request $request,
        CartStorage $cartStorage
    ): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartStorage->getCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new DateTime());

            $cartStorage->setCart($cart);

            return $this->redirectToRoute('product_show', ['key' => $product->getKey()]);
        }

        return $this->render('frontend/product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\CategoryProductType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function listProducts(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/_list_products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @throws Exception
     */
    public function create(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $productImage = $form->get('image')->getData();
            if ($productImage) {
                $productImageName = $fileUploader->upload($productImage);
                $product->setImage($productImageName);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('admin/product/create.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_list');
    }

    public function updateProductCategory(
        Request $request,
        Product $product,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(CategoryProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_add_to_category', ['id' => $product->getId()]);
        }

        /** @var CategoryProductType $form */
        return $this->render('admin/product/add_product_to_category.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
}

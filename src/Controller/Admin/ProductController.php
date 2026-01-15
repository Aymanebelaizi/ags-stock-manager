<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'admin_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepo): Response {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            
            // ✅ Ajout du message de succès
            $this->addFlash('success', 'Product created successfully!');
            
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); 
            
            // ✅ Ajout du message de succès
            $this->addFlash('success', 'Product updated successfully!');

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }
    #[Route('/{id}', name: 'admin_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    } 
    #[Route('/{id}/delete', name: 'admin_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em->remove($product);
            $em->flush();
            
            // ✅ Ajout du message de succès
            $this->addFlash('success', 'Product deleted.');
        }

        return $this->redirectToRoute('admin_product_index');
    }
}
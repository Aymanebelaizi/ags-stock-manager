<?php

namespace App\Controller\Admin;

use App\Entity\PurchaseRequest;
use App\Entity\StockMovement;
use App\Repository\PurchaseRequestRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/purchase-request')]
class PurchaseRequestController extends AbstractController
{
    /**
     * Répare l'erreur InvalidArgumentException (fragment non appelable)
     */
    #[Route('/count-pending', name: 'admin_purchase_count_pending')]
    public function countPending(PurchaseRequestRepository $repo): Response
    {
        $count = $repo->count(['status' => 'pending']);
        return new Response($count > 0 ? '<span class="badge rounded-pill bg-danger ms-auto shadow-sm">' . $count . '</span>' : '');
    }

    #[Route('/', name: 'admin_purchase_index', methods: ['GET'])]
    public function index(PurchaseRequestRepository $repo): Response
    {
        return $this->render('admin/purchase_request/index.html.twig', [
            'purchase_requests' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_purchase_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ProductRepository $pRepo): Response
    {
        if ($request->isMethod('POST')) {
            $product = $pRepo->find($request->request->get('product_id'));
            if ($product) {
                $pr = new PurchaseRequest();
                $pr->setProduct($product);
                $pr->setQuantity((int)$request->request->get('quantity'));
                $pr->setJustification($request->request->get('justification'));
                $pr->setStatus('pending');
                $pr->setRequestedBy($this->getUser());
                $em->persist($pr);
                $em->flush();
                return $this->redirectToRoute('admin_purchase_index');
            }
        }
        return $this->render('admin/purchase_request/new.html.twig', ['products' => $pRepo->findAll()]);
    }

    #[Route('/{id}/edit', name: 'admin_purchase_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PurchaseRequest $pr, EntityManagerInterface $em, ProductRepository $pRepo): Response
    {
        if ($request->isMethod('POST')) {
            $pr->setQuantity((int)$request->request->get('quantity'));
            $pr->setJustification($request->request->get('justification'));
            $em->flush();
            return $this->redirectToRoute('admin_purchase_index');
        }
        return $this->render('admin/purchase_request/edit.html.twig', [
            'pr' => $pr,
            'products' => $pRepo->findAll() # Répare l'erreur image_a791ce.png
        ]);
    }

    #[Route('/{id}/show', name: 'admin_purchase_show', methods: ['GET'])]
    public function show(PurchaseRequest $pr): Response 
    { 
        return $this->render('admin/purchase_request/show.html.twig', ['pr' => $pr]); 
    }

    #[Route('/{id}/approve', name: 'admin_purchase_approve')]
    public function approve(PurchaseRequest $pr, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $product = $pr->getProduct();
            $product->setQuantity($product->getQuantity() + $pr->getQuantity());
            $pr->setStatus('Validée');
            $em->flush();
        }
        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}/reject', name: 'admin_purchase_reject')]
    public function reject(PurchaseRequest $pr, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) { 
            $pr->setStatus('Refusée'); 
            $em->flush(); 
        }
        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}/delete', name: 'admin_purchase_delete', methods: ['POST', 'GET'])]
    public function delete(PurchaseRequest $pr, EntityManagerInterface $em): Response 
    { 
        $em->remove($pr); 
        $em->flush(); 
        return $this->redirectToRoute('admin_purchase_index'); 
    }
}
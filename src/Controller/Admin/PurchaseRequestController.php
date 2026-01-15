<?php

namespace App\Controller\Admin;

use App\Entity\PurchaseRequest;
use App\Entity\StockMovement;
use App\Form\PurchaseRequestType;
use App\Repository\PurchaseRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/purchase-request')]
class PurchaseRequestController extends AbstractController
{
    #[Route('/_count-pending', name: 'admin_purchase_count_pending', methods: ['GET'])]
    public function countPending(PurchaseRequestRepository $repo): Response
    {
        $count = $repo->count(['status' => 'pending']);
        return new Response($count > 0 ? '<span class="badge-premium">'.$count.'</span>' : '');
    }

    #[Route('/', name: 'admin_purchase_index', methods: ['GET'])]
    public function index(PurchaseRequestRepository $repo): Response
    {
        return $this->render('admin/purchase_request/index.html.twig', [
            'purchase_requests' => $repo->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_purchase_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $purchaseRequest = new PurchaseRequest();
        $purchaseRequest->setRequestedBy($this->getUser());
        $purchaseRequest->setStatus('pending');
        $purchaseRequest->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(PurchaseRequestType::class, $purchaseRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($purchaseRequest);
            $em->flush();

            $this->addFlash('success', 'Demande enregistrée avec succès.');
            return $this->redirectToRoute('admin_purchase_index');
        }

        return $this->render('admin/purchase_request/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/approve', name: 'admin_purchase_approve', methods: ['POST'])]
    public function approve(Request $request, PurchaseRequest $pr, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('approve'.$pr->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($pr->getStatus() !== 'pending') {
            return $this->redirectToRoute('admin_purchase_index');
        }

        // 1️⃣ Changer le statut
        $pr->setStatus('approved');

        // 2️⃣ Mettre à jour le stock
        $product = $pr->getProduct();
        $product->setQuantity($product->getQuantity() + $pr->getQuantity());

        // 3️⃣ Créer un mouvement de stock (SANS note)
        $movement = new StockMovement();
        $movement->setProduct($product);
        $movement->setQuantity($pr->getQuantity());
        $movement->setType('ENTREE');
        $movement->setCreatedAt(new \DateTimeImmutable());

        $em->persist($movement);
        $em->flush();

        $this->addFlash('success', 'Demande approuvée et stock mis à jour.');
        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}/reject', name: 'admin_purchase_reject', methods: ['POST'])]
    public function reject(Request $request, PurchaseRequest $pr, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('reject'.$pr->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($pr->getStatus() === 'pending') {
            $pr->setStatus('rejected');
            $em->flush();
            $this->addFlash('danger', 'La demande a été refusée.');
        }

        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}/edit', name: 'admin_purchase_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PurchaseRequest $pr, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PurchaseRequestType::class, $pr);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Demande modifiée.');
            return $this->redirectToRoute('admin_purchase_index');
        }

        return $this->render('admin/purchase_request/edit.html.twig', [
            'form' => $form->createView(),
            'purchase_request' => $pr,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_purchase_delete', methods: ['POST'])]
    public function delete(Request $request, PurchaseRequest $pr, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pr->getId(), $request->request->get('_token'))) {
            $em->remove($pr);
            $em->flush();
            $this->addFlash('warning', 'Demande supprimée.');
        }

        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}', name: 'admin_purchase_show', methods: ['GET'])]
    public function show(PurchaseRequest $pr): Response
    {
        return $this->render('admin/purchase_request/show.html.twig', [
            'pr' => $pr,
        ]);
    }
}

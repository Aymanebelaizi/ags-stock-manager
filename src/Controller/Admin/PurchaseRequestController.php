<?php
namespace App\Controller\Admin;

use App\Entity\PurchaseRequest;
use App\Form\PurchaseRequestType;
use App\Repository\PurchaseRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/purchase-request')]
class PurchaseRequestController extends AbstractController
{
    /**
     * This renders the red badge in your sidebar for pending requests.
     */
    public function countPending(PurchaseRequestRepository $repo): Response
    {
        $count = $repo->count(['status' => 'pending']);
        return new Response($count > 0 ? '<span class="badge bg-danger rounded-pill ms-auto shadow-sm">'.$count.'</span>' : '');
    }

    #[Route('/', name: 'admin_purchase_index', methods: ['GET'])]
    public function index(PurchaseRequestRepository $repo): Response 
    {
        return $this->render('admin/purchase_request/index.html.twig', [
            'purchase_requests' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_purchase_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ProductRepository $productRepo): Response 
    {
        $purchaseRequest = new PurchaseRequest();
        $purchaseRequest->setRequestedBy($this->getUser());
        
        $form = $this->createForm(PurchaseRequestType::class, $purchaseRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($purchaseRequest);
            $em->flush();
            $this->addFlash('success', 'Demande enregistrée.');
            return $this->redirectToRoute('admin_purchase_index');
        }

        return $this->render('admin/purchase_request/new.html.twig', [
            'form' => $form->createView(),
            'products' => $productRepo->findAll(), //
        ]);
    }

    #[Route('/{id}', name: 'admin_purchase_show', methods: ['GET'])]
    public function show(PurchaseRequest $purchaseRequest): Response 
    {
        return $this->render('admin/purchase_request/show.html.twig', [
            // On envoie les deux noms pour être sûr que le template fonctionne
            'purchase_request' => $purchaseRequest,
            'pr' => $purchaseRequest, // Correction pour l'erreur image_e3fc2a.png
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_purchase_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PurchaseRequest $purchaseRequest, EntityManagerInterface $em, ProductRepository $productRepo): Response 
    {
        $form = $this->createForm(PurchaseRequestType::class, $purchaseRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Demande mise à jour avec succès.');
            return $this->redirectToRoute('admin_purchase_index');
        }

        return $this->render('admin/purchase_request/edit.html.twig', [
            'purchase_request' => $purchaseRequest,
            'pr' => $purchaseRequest, // Sécurité si ton template utilise 'pr'
            'form' => $form->createView(),
            'products' => $productRepo->findAll(), // Évite l'erreur "Variable products does not exist"
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_purchase_delete', methods: ['GET'])]
    public function delete(Request $request, PurchaseRequest $purchaseRequest, EntityManagerInterface $em): Response 
    {
        if ($this->isCsrfTokenValid('delete'.$purchaseRequest->getId(), $request->request->get('_token'))) {
            $em->remove($purchaseRequest);
            $em->flush();
            $this->addFlash('danger', 'Demande supprimée.');
        }

        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}/approve', name: 'admin_purchase_approve', methods: ['GET'])]
    public function approve(PurchaseRequest $pr, EntityManagerInterface $em): Response 
    {
        $pr->setStatus('Validée'); 
        $em->flush();
        $this->addFlash('success', 'Demande approuvée.');
        return $this->redirectToRoute('admin_purchase_index');
    }

    #[Route('/{id}/reject', name: 'admin_purchase_reject', methods: ['GET'])]
    public function reject(PurchaseRequest $pr, EntityManagerInterface $em): Response 
    {
        $pr->setStatus('Refusée');
        $em->flush();
        $this->addFlash('warning', 'Demande refusée.');
        return $this->redirectToRoute('admin_purchase_index');
    }
}
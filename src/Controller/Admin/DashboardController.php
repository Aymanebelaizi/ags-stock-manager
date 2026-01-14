<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\StockMovementRepository;
use App\Repository\PurchaseRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(
        ProductRepository $pRepo, 
        StockMovementRepository $mRepo,
        PurchaseRequestRepository $prRepo
    ): Response {
        return $this->render('admin/dashboard/index.html.twig', [
            'totalProducts' => $pRepo->count([]),
            'atRiskCount' => count($pRepo->findByLowStock(5)),
            'shipmentCount' => $mRepo->count([]),
            'categoryData' => $pRepo->getProductsCountByCategory(),
            'pendingRequests' => $prRepo->findBy(['status' => 'pending']), // Variable indispensable
        ]);
    }
}
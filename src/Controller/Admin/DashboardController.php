<?php
namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\PurchaseRequestRepository;
use App\Repository\StockMovementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(
        ProductRepository $productRepo, 
        PurchaseRequestRepository $purchaseRepo,
        StockMovementRepository $moveRepo
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $products = $productRepo->findAll();
        $intelligence = [];

        foreach ($products as $p) {
            $moveCount = count($p->getStockMovements());
            if ($moveCount > 5 && $p->getQuantity() < 10) {
                $intelligence[] = ['name' => $p->getName(), 'status' => 'Rentable', 'color' => 'success'];
            } elseif ($moveCount === 0) {
                $intelligence[] = ['name' => $p->getName(), 'status' => 'Risque', 'color' => 'danger'];
            }
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'totalProducts' => count($products),
            'lowStock' => $productRepo->findBy(['quantity' => 0]),
            'shipments' => $moveRepo->count(['type' => 'IN']),
            'pendingRequests' => $purchaseRepo->findBy(['status' => 'pending']),
            'intelligence' => array_slice($intelligence, 0, 4)
        ]);
    }
}
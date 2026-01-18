<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\PurchaseRequestRepository;
use App\Repository\StockMovementRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(
        ProductRepository $productRepo,
        PurchaseRequestRepository $purchaseRepo,
        StockMovementRepository $moveRepo,
        CategoryRepository $categoryRepo
    ): Response {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

       
        $totalProducts  = $productRepo->count([]);
        $totalMovements = $moveRepo->count([]);

        
        $topProducts = $productRepo->findBy([], ['quantity' => 'DESC'], 5);
        $barData = [['Product', 'Stock']];
        foreach ($topProducts as $product) {
            $barData[] = [$product->getName(), $product->getQuantity()];
        }

        $categories = $categoryRepo->findAll();
        $pieData = [['Category', 'Product Count']];
        foreach ($categories as $category) {
            $count = count($category->getProducts());
            if ($count > 0) {
                $pieData[] = [$category->getName(), $count];
            }
        }

       
        $allProducts = $productRepo->findAll();
        $intelligence = [];

        foreach ($allProducts as $product) {
            $movementCount = count($product->getStockMovements());

            if ($product->getQuantity() > 50 && $movementCount < 2) {
                $intelligence[] = [
                    'name'   => $product->getName(),
                    'status' => 'Stock Dormant',
                    'color'  => 'danger',
                    'icon'   => 'fa-bed'
                ];
            } elseif ($product->getQuantity() < 5) {
                $intelligence[] = [
                    'name'   => $product->getName(),
                    'status' => 'Rupture Proche',
                    'color'  => 'warning',
                    'icon'   => 'fa-exclamation-triangle'
                ];
            }
        }

      
        $pendingRequests = $purchaseRepo->findBy(
            ['status' => 'pending'],
            ['createdAt' => 'DESC'],
            3
        );

       
        return $this->render('admin/dashboard/index.html.twig', [
            'totalProducts'   => $totalProducts,
            'totalMovements'  => $totalMovements,
            'lowStock'        => count($intelligence),
            'pendingRequests' => $pendingRequests,
            'intelligence'    => array_slice($intelligence, 0, 4),
            'barData'         => json_encode($barData),
            'pieData'         => json_encode($pieData),
            'currentDate'     => new \DateTime(),
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\StockMovement;
use App\Form\StockMovementType;
use App\Repository\StockMovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/stock-movement')]
class StockMovementController extends AbstractController
{
    #[Route('/', name: 'admin_movement_index', methods: ['GET'])]
    public function index(StockMovementRepository $repo): Response
    {
        return $this->render('admin/stock_movement/index.html.twig', [
            'movements' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_movement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $movement = new StockMovement();
        $form = $this->createForm(StockMovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $movement->getProduct();
            $qty = $movement->getQuantity();
            $type = $movement->getType();

            if (in_array($type, ['ENTREE', 'IN', 'Entrée'])) {
                $product->setQuantity($product->getQuantity() + $qty);
            } else {
                if ($product->getQuantity() < $qty) {
                    $this->addFlash('danger', 'Erreur : Stock insuffisant.');
                    return $this->redirectToRoute('admin_movement_new');
                }
                $product->setQuantity($product->getQuantity() - $qty);
            }

            $em->persist($movement);
            $em->flush();

            $this->addFlash('success', 'Mouvement enregistré et stock mis à jour.');
            return $this->redirectToRoute('admin_movement_index');
        }

        return $this->render('admin/stock_movement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'admin_movement_show', methods: ['GET'])]
    public function show(StockMovement $movement): Response
    {
        return $this->render('admin/stock_movement/show.html.twig', [
            'movement' => $movement,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_movement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StockMovement $movement, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StockMovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Mouvement modifié avec succès.');
            return $this->redirectToRoute('admin_movement_index');
        }

        return $this->render('admin/stock_movement/edit.html.twig', [
            'movement' => $movement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_movement_delete', methods: ['POST'])]
    public function delete(Request $request, StockMovement $movement, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movement->getId(), $request->request->get('_token'))) {
            $em->remove($movement);
            $em->flush();
            $this->addFlash('warning', 'Mouvement supprimé.');
        }

        return $this->redirectToRoute('admin_movement_index');
    }
}
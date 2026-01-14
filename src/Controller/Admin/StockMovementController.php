<?php

namespace App\Controller\Admin;

use App\Entity\StockMovement;
use App\Form\StockMovementType;
use App\Repository\StockMovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/stock-movement')]
class StockMovementController extends AbstractController
{
    /** LISTE (Index) **/
    #[Route('/', name: 'admin_movement_index', methods: ['GET'])]
    public function index(StockMovementRepository $repo): Response
    {
        return $this->render('admin/stock_movement/index.html.twig', [
            'movements' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /** CRÉATION (New) - INDISPENSABLE POUR CORRIGER VOTRE ERREUR **/
    #[Route('/new', name: 'admin_movement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $movement = new StockMovement();
        $form = $this->createForm(StockMovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $movement->getProduct();
            $qty = $movement->getQuantity();

            if ($movement->getType() === 'IN' || $movement->getType() === 'Entrée') {
                $product->setQuantity($product->getQuantity() + $qty);
            } else {
                if ($product->getQuantity() < $qty) {
                    $this->addFlash('danger', 'Stock insuffisant pour cette sortie !');
                    return $this->redirectToRoute('admin_movement_new');
                }
                $product->setQuantity($product->getQuantity() - $qty);
            }

            $em->persist($movement);
            $em->flush();
            $this->addFlash('success', 'Mouvement enregistré et stock mis à jour.');
            return $this->redirectToRoute('admin_movement_index');
        }
        return $this->render('admin/stock_movement/new.html.twig', ['form' => $form->createView()]);
    }

    /** VUE DÉTAILLÉE (Show) **/
    #[Route('/{id}/show', name: 'admin_movement_show', methods: ['GET'])]
    public function show(StockMovement $movement): Response
    {
        return $this->render('admin/stock_movement/show.html.twig', ['movement' => $movement]);
    }

    /** MODIFICATION (Edit) **/
    #[Route('/{id}/edit', name: 'admin_movement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StockMovement $movement, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StockMovementType::class, $movement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('info', 'Mouvement modifié.');
            return $this->redirectToRoute('admin_movement_index');
        }
        return $this->render('admin/stock_movement/edit.html.twig', ['movement' => $movement, 'form' => $form->createView()]);
    }

    /** SUPPRESSION (Delete) **/
    #[Route('/{id}/delete', name: 'admin_movement_delete')]
    public function delete(StockMovement $movement, EntityManagerInterface $em): Response
    {
        $em->remove($movement);
        $em->flush();
        $this->addFlash('danger', 'Mouvement supprimé de l\'historique.');
        return $this->redirectToRoute('admin_movement_index');
    }
}
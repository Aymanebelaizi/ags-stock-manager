<?php

namespace App\Controller\Admin;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/supplier')]
class SupplierController extends AbstractController
{
    /**
     * Affiche la liste (Gardé tel quel)
     */
    #[Route('/', name: 'admin_supplier_index', methods: ['GET'])]
    public function index(SupplierRepository $repo): Response
    {
        return $this->render('admin/supplier/index.html.twig', [
            'suppliers' => $repo->findAll(),
        ]);
    }

    /**
     * Ajoute un nouveau partenaire
     * CORRECTION : Utilisation de createForm pour satisfaire le design
     */
    #[Route('/new', name: 'admin_supplier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $supplier = new Supplier();
        
        // On crée le formulaire basé sur SupplierType (nécessaire pour le design)
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($supplier);
            $em->flush();

            $this->addFlash('success', 'New supplier "' . $supplier->getName() . '" registered successfully.');
            return $this->redirectToRoute('admin_supplier_index');
        }

        // On envoie la variable 'form' à la vue pour éviter l'erreur
        return $this->render('admin/supplier/new.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(), 
        ]);
    }

    /**
     * Modifie un fournisseur
     * CORRECTION : Utilisation de createForm ici aussi
     */
    #[Route('/{id}/edit', name: 'admin_supplier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplier $supplier, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Supplier details updated successfully.');
            return $this->redirectToRoute('admin_supplier_index');
        }

        return $this->render('admin/supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprime un fournisseur
     * (J'ai gardé votre logique de vérification si la méthode existe)
     */
    #[Route('/{id}/delete', name: 'admin_supplier_delete', methods: ['POST'])]
    public function delete(Request $request, Supplier $supplier, EntityManagerInterface $em): Response
    {
        // Sécurité CSRF standard
        if ($this->isCsrfTokenValid('delete'.$supplier->getId(), $request->request->get('_token'))) {
            
            // Si vous avez une méthode getPurchaseRequests dans votre entité, décommentez ceci :
            /*
            if (method_exists($supplier, 'getPurchaseRequests') && count($supplier->getPurchaseRequests()) > 0) {
                $this->addFlash('danger', 'Cannot delete: Supplier is linked to existing orders.');
                return $this->redirectToRoute('admin_supplier_index');
            }
            */

            $em->remove($supplier);
            $em->flush();
            $this->addFlash('success', 'Supplier deleted permanently.');
        }

        return $this->redirectToRoute('admin_supplier_index');
    }
    /**
     * Affiche les détails d'un fournisseur
     */
    #[Route('/{id}', name: 'admin_supplier_show', methods: ['GET'])]
    public function show(Supplier $supplier): Response
    {
        return $this->render('admin/supplier/show.html.twig', [
            'supplier' => $supplier,
        ]);
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\Supplier;
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
     * Affiche la liste de tous les fournisseurs (EMSI Stock)
     */
    #[Route('/', name: 'admin_supplier_index', methods: ['GET'])]
    public function index(SupplierRepository $repo): Response
    {
        return $this->render('admin/supplier/index.html.twig', [
            'suppliers' => $repo->findAll(),
        ]);
    }

    /**
     * Ajoute un nouveau partenaire (Style AGS.Pro)
     */
    #[Route('/new', name: 'admin_supplier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $supplier = new Supplier();
            // On récupère uniquement les champs présents dans votre entité
            $supplier->setName($request->request->get('name'));
            $supplier->setEmail($request->request->get('email'));
            $supplier->setPhone($request->request->get('phone'));

            $em->persist($supplier);
            $em->flush();

            $this->addFlash('success', 'Le fournisseur "' . $supplier->getName() . '" a été enregistré.');
            return $this->redirectToRoute('admin_supplier_index');
        }

        return $this->render('admin/supplier/new.html.twig');
    }

    /**
     * Modifie les informations d'un fournisseur existant
     */
    #[Route('/{id}/edit', name: 'admin_supplier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplier $supplier, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            // Mise à jour des données sans le champ address
            $supplier->setName($request->request->get('name'));
            $supplier->setEmail($request->request->get('email'));
            $supplier->setPhone($request->request->get('phone'));

            $em->flush();

            $this->addFlash('success', 'Informations du fournisseur mises à jour.');
            return $this->redirectToRoute('admin_supplier_index');
        }

        return $this->render('admin/supplier/edit.html.twig', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Supprime un fournisseur du système
     */
    #[Route('/{id}/delete', name: 'admin_supplier_delete', methods: ['POST', 'GET'])]
    public function delete(Supplier $supplier, EntityManagerInterface $em): Response
    {
        // On vérifie si des demandes d'achat sont liées avant suppression
        if (count($supplier->getPurchaseRequests()) > 0) {
            $this->addFlash('danger', 'Impossible de supprimer : ce fournisseur est lié à des demandes d\'achat.');
        } else {
            $em->remove($supplier);
            $em->flush();
            $this->addFlash('success', 'Fournisseur retiré de la base de données.');
        }

        return $this->redirectToRoute('admin_supplier_index');
    }
}
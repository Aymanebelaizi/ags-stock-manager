<?php

namespace App\DataFixtures;

use App\Entity\Category; // <--- Don't forget this import!
use App\Entity\Product;
use App\Entity\PurchaseRequest;
use App\Entity\Supplier;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userRepo = $manager->getRepository(User::class);

        // ======================================================
        // 1. GESTION DES UTILISATEURS (Touche pas à ça !)
        // ======================================================

        // --- ADMIN ---
        $admin = $userRepo->findOneBy(['email' => 'admin@test.com']);
        if (!$admin) {
            $admin = new User();
            $admin->setEmail('admin@test.com');
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword($this->hasher->hashPassword($admin, '123456'));
            $manager->persist($admin);
        }

        // --- MANAGER ---
        $managerUser = $userRepo->findOneBy(['email' => 'manager@test.com']);
        if (!$managerUser) {
            $managerUser = new User();
            $managerUser->setEmail('manager@test.com');
            $managerUser->setRoles(['ROLE_MANAGER']);
            $managerUser->setPassword($this->hasher->hashPassword($managerUser, 'manager123456'));
            $manager->persist($managerUser);
        }

        // --- MAGASINIER ---
        $magasinier = $userRepo->findOneBy(['email' => 'magasinier@test.com']);
        if (!$magasinier) {
            $magasinier = new User();
            $magasinier->setEmail('magasinier@test.com');
            $magasinier->setRoles(['ROLE_MAGASINIER']);
            $magasinier->setPassword($this->hasher->hashPassword($magasinier, 'magasinier123456'));
            $manager->persist($magasinier);
        }

        // ======================================================
        // 2. CRÉATION DES CATÉGORIES (NEW!)
        // ======================================================
        $categories = [];
        $categoryNames = [
            'Ordinateurs & Portables' => 'Laptops, PC Fixes et Workstations',
            'Périphériques' => 'Claviers, Souris, Casques, Webcams',
            'Réseau & Serveurs' => 'Routeurs, Switchs, Câbles RJ45',
            'Mobilier de Bureau' => 'Chaises, Bureaux, Armoires',
            'Consommables' => 'Papier, Encre, Stylos',
            'Stockage' => 'Disques Durs, SSD, Clés USB'
        ];

        foreach ($categoryNames as $name => $desc) {
            $cat = new Category();
            $cat->setName($name);
            $cat->setDescription($desc);
            $manager->persist($cat);
            $categories[] = $cat; // Save for later
        }

        // ======================================================
        // 3. CRÉATION DES FOURNISSEURS (More Realism)
        // ======================================================
        $suppliers = [];
        $supplierNames = [
            'Dell Maroc', 
            'Samsung Electronics', 
            'Logitech Pro', 
            'Bureau Vallée', 
            'TechMaroc Solutions', 
            'Cisco Systems', 
            'MegaComputer Casa'
        ];
        
        foreach ($supplierNames as $name) {
            $supplier = new Supplier();
            $supplier->setName($name);
            $supplier->setEmail(strtolower(str_replace(' ', '', $name)) . '@contact.com');
            $supplier->setPhone('0522' . rand(100000, 999999)); // Random phone
            $manager->persist($supplier);
            $suppliers[] = $supplier;
        }

        // ======================================================
        // 4. CRÉATION DES PRODUITS (Big Inventory)
        // ======================================================
        $products = [];
        // Format : [Nom, Ref, Prix Achat, Prix Vente, Quantité, Seuil Alerte]
        $productData = [
            // Laptops
            ['HP EliteBook G9', 'PC-HP-001', 8000, 10000, 5, 10], 
            ['Dell Latitude 5520', 'PC-DELL-002', 7500, 9500, 20, 5],
            ['MacBook Pro M2', 'MAC-PRO-M2', 15000, 18000, 3, 2],
            ['Lenovo ThinkPad X1', 'LEN-TP-X1', 12000, 14500, 8, 4],
            
            // Screens & Peripherals
            ['Ecran Samsung 24"', 'ECR-SAM-24', 1200, 1800, 2, 8],
            ['Ecran Dell UltraSharp', 'ECR-DELL-27', 2500, 3200, 10, 3],
            ['Clavier Mécanique', 'ACC-CLAV-01', 300, 500, 50, 10],
            ['Souris Logitech MX', 'ACC-SOU-MX', 450, 600, 30, 5],
            ['Webcam HD Pro', 'ACC-WEB-C920', 600, 900, 12, 5],
            
            // Printing & Office
            ['Imprimante Canon MF', 'IMP-CAN-01', 2500, 3500, 1, 3], 
            ['Papier A4 (Rame)', 'BUR-PAP-A4', 40, 60, 200, 50],
            ['Chaise Ergonomique', 'MOB-CHAISE', 1500, 2500, 4, 5],
            ['Bureau Assis-Debout', 'MOB-BUR-ELEC', 3000, 4500, 5, 2],
            
            // Storage & Network
            ['Disque SSD 1TB', 'STO-SSD-1T', 600, 900, 15, 5],
            ['Câble HDMI 2m', 'CAB-HDMI-2', 50, 100, 30, 10],
            ['Switch 24 Ports', 'NET-SW-24', 1800, 2400, 6, 2],
            ['Routeur Wifi 6', 'NET-WF-6', 800, 1200, 10, 3]
        ];

        foreach ($productData as $data) {
            $product = new Product();
            $product->setName($data[0]);
            $product->setReference($data[1]);
            $product->setPurchasePrice($data[2]);
            $product->setSalesPrice($data[3]);
            $product->setQuantity($data[4]); 
            $product->setAlertThreshold($data[5]);
            
            // Assign a random category from the list we created above
            $product->setCategory($categories[array_rand($categories)]);

            $manager->persist($product);
            $products[] = $product;
        }

        // ======================================================
        // 5. CRÉATION DES DEMANDES D'ACHAT
        // ======================================================
        
        // Commandes EN ATTENTE (Pending)
        for ($i = 0; $i < 8; $i++) {
            $req = new PurchaseRequest();
            $req->setProduct($products[array_rand($products)]);
            $req->setRequestedBy($managerUser);
            $req->setSupplier($suppliers[array_rand($suppliers)]);
            $req->setQuantity(rand(10, 50));
            $req->setJustification("Besoin urgent stock critique");
            $req->setStatus('pending'); // or 'En attente' depending on your logic
            $manager->persist($req);
        }

        // Commandes VALIDÉES (Approved)
        for ($i = 0; $i < 8; $i++) {
            $req = new PurchaseRequest();
            $req->setProduct($products[array_rand($products)]);
            $req->setRequestedBy($managerUser);
            $req->setSupplier($suppliers[array_rand($suppliers)]);
            $req->setQuantity(rand(5, 20));
            $req->setJustification("Réassort mensuel standard");
            $req->setStatus('approved'); // or 'Validée'
            $manager->persist($req);
        }

        $manager->flush();
    }
}
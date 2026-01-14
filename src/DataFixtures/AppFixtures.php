<?php

namespace App\DataFixtures;

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
        // Création de l'Administrateur [cite: 8]
        $admin = new User();
        $admin->setEmail('admin@emsi.ma');
        $admin->setRoles(['ROLE_ADMIN']); // [cite: 92]
        
        // Hachage du mot de passe "admin123"
        $password = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();
    }
}
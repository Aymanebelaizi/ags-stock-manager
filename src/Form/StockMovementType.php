<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\StockMovement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockMovementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* 1. Sélection du produit (Lien avec l'entité Product) */
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name', // Affiche le nom du produit dans la liste
                'label' => 'Sélectionner le Produit',
                'attr' => [
                    'class' => 'form-select bg-light border-0 py-3 shadow-sm',
                ],
            ])

            /* 2. Type de mouvement (Entrée ou Sortie) */
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Entrée (+) Stock' => 'IN',
                    'Sortie (-) Stock' => 'OUT',
                ],
                'label' => 'Action à réaliser',
                'attr' => [
                    'class' => 'form-select bg-light border-0 py-3 shadow-sm',
                ],
            ])

            /* 3. Quantité */
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'form-control bg-light border-0 py-3 shadow-sm',
                    'min' => 1,
                    'placeholder' => 'Nombre d\'unités'
                ],
            ])

            /* 4. Commentaire (La propriété qui posait problème) */
            ->add('comment', TextType::class, [
                'label' => 'Motif / Commentaire (Optionnel)',
                'required' => false, // Ce champ peut être vide
                'attr' => [
                    'class' => 'form-control bg-light border-0 py-3 shadow-sm',
                    'placeholder' => 'Ex: Livraison de maintenance, Commande client...'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockMovement::class, // Relie le formulaire à l'entité
        ]);
    }
}
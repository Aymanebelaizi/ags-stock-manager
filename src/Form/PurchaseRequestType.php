<?php

namespace App\Form;

use App\Entity\PurchaseRequest;
use App\Entity\Product;
use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Produit'
            ])
            ->add('quantity', null, [ // Utilise 'quantity' et non 'requestedQuantity'
                'label' => 'Quantité'
            ])
            ->add('justification', null, [
                'label' => 'Justification (Optionnel)'
            ]);
    }
}
<?php

namespace App\Form;

use App\Entity\StockMovement;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockMovementType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Operation Type', // Anglais
                'choices'  => [
                    '🟢 Stock Entry (IN)' => 'IN',  // Anglais
                    '🔴 Stock Exit (OUT)' => 'OUT', // Anglais
                ],
                'placeholder' => 'Select type...',
                'attr' => ['class' => 'form-select']
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => function ($product) {
                    // Petite astuce : Affiche "Nom (Stock: 12)"
                    return $product->getName() . ' (Stock: ' . $product->getQuantity() . ')';
                },
                'label' => 'Target Product', // Anglais
                'placeholder' => 'Select a product...',
                'attr' => ['class' => 'form-select']
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity', // Anglais
                'attr' => ['min' => 1, 'class' => 'form-control', 'placeholder' => 'Ex: 50']
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Note / Reason', // Anglais
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockMovement::class,
        ]);
    }
}
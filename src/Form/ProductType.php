<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'attr' => ['placeholder' => 'Ex: HP EliteBook G9']
            ])
            ->add('reference', TextType::class, [
                'label' => 'Reference (SKU)',
                'attr' => ['placeholder' => 'Ex: PC-HP-001']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category',
                'placeholder' => 'Select a category...',
                'attr' => ['class' => 'form-select']
            ])
            // SUPPRESSION DU CHAMP SUPPLIER ICI
            ->add('quantity', IntegerType::class, [
                'label' => 'Initial Stock',
                'attr' => ['placeholder' => '0']
            ])
            ->add('alertThreshold', IntegerType::class, [
                'label' => 'Alert Threshold',
                'attr' => ['placeholder' => '5']
            ])
            ->add('purchasePrice', MoneyType::class, [
                'currency' => 'MAD',
                'label' => 'Purchase Price',
                'required' => false,
                'attr' => ['placeholder' => '0.00']
            ])
            ->add('salesPrice', MoneyType::class, [
                'currency' => 'MAD',
                'label' => 'Sales Price',
                'required' => false,
                'attr' => ['placeholder' => '0.00']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
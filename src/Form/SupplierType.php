<?php

namespace App\Form;

use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Company Name',
                'attr' => ['placeholder' => 'Ex: Samsung, DHL...']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr' => ['placeholder' => 'contact@company.com']
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['placeholder' => '+212 6...']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Full Address',
                'required' => false,
                'attr' => ['rows' => 3, 'placeholder' => 'Street, City, Country...']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Supplier::class]);
    }
}
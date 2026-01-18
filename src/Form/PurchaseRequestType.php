<?php

namespace App\Form;

use App\Entity\PurchaseRequest;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => fn (Product $p) => sprintf('%s (%s)', $p->getName(), $p->getReference()),
                'placeholder' => '-- Sélectionner un produit --',
                'label' => false,
                'required' => true,
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-select border-0 bg-light rounded-3 py-3',
                ],
            ])

            ->add('quantity', IntegerType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control border-0 bg-light rounded-3 py-3',
                    'placeholder' => 'Ex: 10',
                    'min' => 1,
                ],
                'empty_data' => '1',
            ])

            ->add('justification', TextareaType::class, [
                'label' => false,
                'required' => false, // mettez true si vous voulez l’obliger
                'attr' => [
                    'class' => 'form-control border-0 bg-light rounded-3 py-3',
                    'rows' => 4,
                    'placeholder' => 'Expliquez pourquoi ce matériel est nécessaire...',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PurchaseRequest::class,
        ]);
    }
}

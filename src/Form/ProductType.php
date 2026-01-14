<?php
namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{NumberType, TextType, IntegerType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control bg-light border-0 py-3']])
            ->add('reference', TextType::class, ['attr' => ['class' => 'form-control bg-light border-0 py-3']])
            ->add('purchasePrice', NumberType::class, ['attr' => ['class' => 'form-control bg-light border-0 py-3']])
            ->add('salesPrice', NumberType::class, ['attr' => ['class' => 'form-control bg-light border-0 py-3']])
            ->add('quantity', IntegerType::class, ['attr' => ['class' => 'form-control bg-light border-0 py-3']])
            ->add('alertThreshold', IntegerType::class, ['attr' => ['class' => 'form-control bg-light border-0 py-3']])
            ->add('category', EntityType::class, [
                'class' => Category::class, 'choice_label' => 'name',
                'attr' => ['class' => 'form-select bg-light border-0 py-3']
            ]);
            // LE CHAMP SUPPLIER A ÉTÉ SUPPRIMÉ ICI
    }

    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults(['data_class' => Product::class]); }
}
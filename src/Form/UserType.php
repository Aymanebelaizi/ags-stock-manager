<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control bg-light border-0 py-3']
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Admin'   => 'ROLE_ADMIN',
                    'Manager' => 'ROLE_MANAGER',
                ],
                'multiple' => true,
                'expanded' => true, // This makes them checkboxes
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control bg-light border-0 py-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userName', TextType::class, [
                'attr' => [
                    'class' => 'input-field',
                    'placeholder' => 'Enter your Name',
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'input-field',
                    'placeholder' => 'Enter your Last name',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit changes',
                'attr' => [
                    'class' => 'btn-submit',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}

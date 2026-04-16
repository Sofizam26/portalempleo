<?php

namespace App\Form\Registro;

use App\Entity\RegistroPendiente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroAnuncianteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreAnunciante', TextType::class, [
                'label' => 'Nombre del anunciante',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'mapped' => false,
            ])
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => [
                    'Empresa' => 'empresa',
                    'Particular' => 'particular',
                ],
            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('sitioWeb', TextType::class, [
                'label' => 'Sitio web',
                'required' => false,
            ])
            ->add('ciudad', TextType::class, [
                'label' => 'Ciudad',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistroPendiente::class,
        ]);
    }
}
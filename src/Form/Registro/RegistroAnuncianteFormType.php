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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistroAnuncianteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreAnunciante', TextType::class, [
                'label' => 'Nombre del anunciante',
                'constraints' => [
                    new NotBlank(['message' => 'El nombre es obligatorio']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new NotBlank(['message' => 'El email es obligatorio']),
                    new Email(['message' => 'Introduce un email válido']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'La contraseña es obligatoria']),
                ],
            ])
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => [
                    'Empresa' => 'empresa',
                    'Particular' => 'particular',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Selecciona un tipo']),
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

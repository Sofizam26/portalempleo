<?php
namespace App\Form;

use App\Entity\Oferta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OfertaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título'
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción'
            ])
            ->add('salario', TextType::class, [
                'label' => 'Salario'
            ])
            ->add('tipo_contrato', TextType::class, [
                'label' => 'Tipo de contrato'
            ])
            ->add('ciudad', TextType::class, [
                'label' => 'Ciudad'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Oferta::class,
        ]);
    }
}
?>
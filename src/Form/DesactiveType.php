<?php

namespace App\Form;

use App\Entity\Desactive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DesactiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Debut',

            ])
            ->add('fin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fin',

            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Desactive::class,
        ]);
    }
}
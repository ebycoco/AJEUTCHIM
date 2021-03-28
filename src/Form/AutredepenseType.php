<?php

namespace App\Form;

use App\Entity\Autredepense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutredepenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', IntegerType::class)
            ->add('motif')
            ->add('jourpris', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Le jour pris',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Autredepense::class,
        ]);
    }
}

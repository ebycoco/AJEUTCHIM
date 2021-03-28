<?php

namespace App\Form;

use App\Entity\DemandeAdhesion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeAdhesionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('profession')
            ->add('contact')
            ->add('nomPere')
            ->add('nomMere')
            ->add('motivation')
            ->add('droit', CheckboxType::class, [
                'label' => 'j\'accepte d\'être contacter par l\'équipe d\'AJEUTCHIM  et donner d\'autres informations.'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeAdhesion::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Mandat;
use App\Entity\Membre;
use App\Entity\PostAjeutchim;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MandatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('membre', EntityType::class, [
                'class' => Membre::class,
                'choice_label' => 'prenom'
            ])
            ->add('postAjeutchim', EntityType::class, [
                'class' => PostAjeutchim::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mandat::class,
        ]);
    }
}
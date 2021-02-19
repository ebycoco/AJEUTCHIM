<?php

namespace App\Form;

use App\Entity\Adhesion;
use App\Entity\Membre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('ville')
            ->add('contact')
            ->add('profession')
            ->add('email')
            ->add('referenceAjeutchim')
            ->add('adhesion', EntityType::class, [
                'class' => Adhesion::class,
                'choice_label' => 'montant'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
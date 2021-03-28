<?php

namespace App\Form;

use App\Entity\Annee;
use App\Entity\Bilan;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annee')
            // ->add('annee', EntityType::class, [
            //     'class' => Annee::class,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er
            //             ->createQueryBuilder('a')
            //             ->orderBy('a.id', 'DESC');
            //     },
            //     'choice_label' => 'annee',
            //     'label' => 'Recherche par année',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bilan::class,
        ]);
    }
}
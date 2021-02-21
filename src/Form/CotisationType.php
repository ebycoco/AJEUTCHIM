<?php

namespace App\Form;

use App\Entity\Cotisation;
use App\Entity\Membre;
use App\Entity\MontantAnnuelle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant')
            ->add('annee', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Année de cotisation',

            ])
            ->add('membre', EntityType::class, [
                'class' => Membre::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('m')
                        ->orderBy('m.id', 'DESC');
                },
                'choice_label' => 'prenom',
                'label' => 'Membre Ajeutchim',
            ])
            ->add('montantAnnuelle', EntityType::class, [
                'class' => MontantAnnuelle::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('ma')
                        ->orderBy('ma.id', 'DESC');
                },
                'choice_label' => 'montant',
                'label' => 'montant Annuelle',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cotisation::class,
        ]);
    }
}
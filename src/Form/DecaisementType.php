<?php

namespace App\Form;

use App\Entity\Decaisement;
use App\Entity\Membre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecaisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant')
            ->add('frais')
            ->add('jour', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Jour de transaction',

            ])
            ->add('membre', EntityType::class, [
                'class' => Membre::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('m')
                        ->orderBy('m.id', 'DESC');
                },
                'choice_label' => 'prenom',
                'label' => 'Remise à',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Decaisement::class,
        ]);
    }
}
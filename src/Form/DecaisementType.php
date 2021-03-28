<?php

namespace App\Form;

use App\Entity\Bureau;
use App\Entity\Decaisement;
use App\Entity\Membre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecaisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', IntegerType::class)
            ->add('frais')
            ->add('jour', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Jour de transaction',

            ])
            ->add('bureau', EntityType::class, [
                'class' => Bureau::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('b')
                        ->andWhere('b.etat = 0')
                        ->orderBy('b.id', 'DESC');
                },
                'choice_label' => 'membre',
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

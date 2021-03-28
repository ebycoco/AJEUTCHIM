<?php

namespace App\Form;

use App\Entity\AnnuelleCotisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnuelleCotisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', IntegerType::class)
            ->add('createdAt')
            ->add('updatedAt')
            ->add('membre');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnuelleCotisation::class,
        ]);
    }
}

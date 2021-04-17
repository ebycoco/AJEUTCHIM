<?php

namespace App\Form;

use App\Entity\Depense;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => false,
                'label' => 'nom du projet',
                'attr' => [
                    'placeholder' => 'Entrer le nom du projet'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'config_name' => 'main_config',
            ])
            ->add('montant', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Entrer le montant'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depense::class,
        ]);
    }
}
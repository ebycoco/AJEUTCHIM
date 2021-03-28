<?php

namespace App\Form;

use App\Entity\Village;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VillageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('histoire',CKEditorType::class, [
                'config_name' => 'main_config',
                    ])
            ->add('imageName')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Village::class,
        ]);
    }
}
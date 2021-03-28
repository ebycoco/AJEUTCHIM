<?php

namespace App\Form;

use App\Entity\Ajeutchim;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjeutchimType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenue',CKEditorType::class, [
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
            'data_class' => Ajeutchim::class,
        ]);
    }
}
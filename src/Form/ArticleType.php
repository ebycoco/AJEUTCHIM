<?php

namespace App\Form;

use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', CKEditorType::class, [
                'config_name' => 'main_config',
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_link' => false,
                'image_uri' => false,
                'label' => 'Image d\'article (JPG or PNG file)',
                'attr' => [
                    'class' => 'filestyle',
                    'data-buttonname' => 'btn-secondary',
                ]

            ])
            ->add('categorie');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
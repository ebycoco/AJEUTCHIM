<?php

namespace App\Form;

use App\Entity\Versement;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', TextType::class)
            ->add('objet', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('description', CKEditorType::class, [
                'config_name' => 'main_config',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom de la personne',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
        ]);
    }

    public function getChoices()
    {
        $choices = Versement::OBJET;
        $output = [];
        foreach ($choices as $key => $value) {
            $output[$value] = $key;
        }
        return $output;
    }
}

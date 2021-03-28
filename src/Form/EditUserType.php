<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse email',
                    ])
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('pseudo')
            ->add('matricule')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Président' => 'ROLE_PRESI',
                    'Vice président' => 'ROLE_VICE',
                    'Secrétaire générale' => 'ROLE_SECRE',
                    'Secrétaire générale adjoint' => 'ROLE_SECREA',
                    'Secrétaire chargé a l\'organisation' => 'ROLE_SECREO',
                    'Secrétaire chargé a l\'organisation 1' => 'ROLE_SECREOA1',
                    'Secrétaire chargé a l\'organisation 2' => 'ROLE_SECREOA2',
                    'Trésorier' => 'ROLE_TRESO',
                    'Trésorier adjoint' => 'ROLE_TRESOA',
                    'Secrétaire chargé aux affaires exterieures' => 'ROLE_SCAE',
                    'Secrétaire chargé aux affaires exterieures adjoint' => 'ROLE_SCAEA',
                    'Secrétaire chargé a l\'information et à la communication' => 'ROLE_SCIC',
                    'Secrétaire chargé a l\'information et à la communication adjoint' => 'ROLE_SCICA',
                    'Secrétaire chargé aux affaires sociales' => 'ROLE_SCAS',
                    'Secrétaire chargé aux affaires sociales adjoint' => 'ROLE_SCASA',
                    'Secrétaire chargé aux affaires culturelles' => 'ROLE_SCAC',
                    'Secrétaire chargé aux affaires culturelles adjoint 1' => 'ROLE_SCACA1',
                    'Secrétaire chargé aux affaires culturelles adjoint 2' => 'ROLE_SCACA2',
                    'Responsable des femmes' => 'ROLE_RF',
                    'Responsable des femmes adjoint 1' => 'ROLE_RFA1',
                    'Responsable des femmes adjoint 2' => 'ROLE_RFA2',
                    'Maître de séance' => 'ROLE_MS',
                    'Maître de séance adjoint' => 'ROLE_MSA',
                    'Commissaire au compte' => 'ROLE_CC',
                    'Commissaire au compte adjoint' => 'ROLE_CCA',
                    'Secrétaire chargé de la relation avec la jeunesse rurale' => 'ROLE_SCRJR',
                    'Secrétaire chargé de la relation avec la jeunesse rurale adjoint' => 'ROLE_SCRJRA',
                    'Secrétaire chargé des études' => 'ROLE_SCE',
                    'Secrétaire chargé des études adjoint' => 'ROLE_SCEA',
                    'Secrétaire chargé de l\'entrepreneuriat' => 'ROLE_SCEN',
                    'Secrétaire chargé de l\'entrepreneuriat adjoint' => 'ROLE_SCENA',
                    'Conseillers' => 'ROLE_CONSE',
                    'Parraineur' => 'ROLE_PAR',
                    'Utilisateur' => 'ROLE_USER',
                    'Tresorier' => 'ROLE_EDIT',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles',
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
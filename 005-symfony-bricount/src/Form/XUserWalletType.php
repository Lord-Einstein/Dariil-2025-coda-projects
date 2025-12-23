<?php

namespace App\Form;

use App\DTO\XUserWalletDTO;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class XUserWalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        /** @var User[] $availableUsers */
        $availableUsers = $options['available_users'];

        $builder
            ->add('user', ChoiceType::class, [
                'choices' => $availableUsers,
                //formater l'affichage des users dans mon formulaire
                'choice_label' => function (User $user) {
                    return $user->getName() . ' (' . $user->getEmail() . ')';
                },
                //utiliser les id coe valeur de choix
                'choice_value' => 'id',
                'placeholder' => 'Choisir une personne...',
                'attr' => ['class' => 'form-select']
            ])
            ->add('role', ChoiceType::class, [

                'choices' => [
                    'Membre' => 'user',
                    'Administrateur' => 'admin',
                ],
                'expanded' => true, // pour avoir des boutons Radio
                'multiple' => false,
                'data' => 'user' // Valeur par défaut pour le role
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => XUserWalletDTO::class,
            'available_users' => [],
        ]);

        //typage de mon option
        $resolver->setAllowedTypes('available_users', 'array');

    }
}

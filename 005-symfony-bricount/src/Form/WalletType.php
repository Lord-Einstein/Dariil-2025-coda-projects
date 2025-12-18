<?php

namespace App\Form;

use App\Controller\Wallets\DTO\WalletDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du portefeuille.',
                'attr' => ['placeholder' => 'Ex : Randonnée en Taïwan...']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer Portefeuille',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // C'est ici que je dois revenir faire la connec avec le DTO
            'data_class' => WalletDTO::class,
            'csrf_protection' => true
        ]);
    }
}


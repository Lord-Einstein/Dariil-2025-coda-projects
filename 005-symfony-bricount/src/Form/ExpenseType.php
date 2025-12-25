<?php

namespace App\Form;

use App\DTO\ExpenseDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenseType extends AbstractType
{

    //Liste en dur de mes icones
    private const ICONS = [
        'Restaurant' => 'fa-solid fa-utensils',
        'Courses' => 'fa-solid fa-cart-shopping',
        'Bar' => 'fa-solid fa-beer-mug-empty',
        'Transport' => 'fa-solid fa-bus',
        'Logement' => 'fa-solid fa-house',
        'Loisir' => 'fa-solid fa-gamepad',
        'Santé' => 'fa-solid fa-stethoscope',
        'Cadeau' => 'fa-solid fa-gift',
        'Randonnée' => 'fa-solid fa-mountain',
        'Fête' => 'fa-solid fa-champagne-glasses',
        'Shopping' => 'fa-solid fa-shirt',
        'Voyage' => 'fa-solid fa-plane',
        'Abonnements' => 'fa-solid fa-tv',

        'Autre' => 'fa-solid fa-receipt'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'Montant',
                'currency' => 'EUR',
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'input-amount'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => '',
                    'rows' => 3,
                ],
                'required' => true,
            ])
            ->add('icon', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => self::ICONS,
                'expanded' => true,
                'multiple' => false,
                'label_attr' => ['class' => 'radio-inline'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExpenseDTO::class,
        ]);
    }

}

<?php

namespace App\Form;

use App\Entity\Dish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Dish Name',
                'attr' => [
                'type' => 'number',
                'step' => '0.01', // allows decimals
                'min' => '0',
                'class' => 'w-full rounded-lg bg-[var(--bg-input)] border border-[var(--brand-gold)]/30 p-3 text-[var(--text-light)]',
                'placeholder' => 'Enter Dish',]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'required' => true,
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                    'class' => 'form-control',
                    'placeholder' => 'Enter price',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Appetizer' => 'Appetizer',
                    'Main Course' => 'Main Course',
                    'Dessert' => 'Dessert',
                    'Beverage' => 'Beverage',
                ],
                'placeholder' => 'Select a category',
            ])
            ->add('image', FileType::class, [
                 'label' => 'Dish Image (optional)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
        ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Customer_Name', TextType::class, [
                'label' => 'Full Name',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your name.']),
                    new Assert\Length(['max' => 100]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your email address.']),
                    new Assert\Email(['message' => 'Please enter a valid email address.']),
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Phone Number',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter your phone number.']),
                    new Assert\Regex([
                        'pattern' => '/^[0-9+\-\s]{7,15}$/',
                        'message' => 'Please enter a valid phone number.',
                    ]),
                ],
            ])
            ->add('Date', DateTimeType::class, [
                'label' => 'Reservation Date & Time',
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select a date and time.']),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'The reservation date must be in the future.',
                    ]),
                ],
            ])
            ->add('guests', IntegerType::class, [
                'label' => 'Number of Guests',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please enter how many guests.']),
                    new Assert\Positive(['message' => 'Guest count must be positive.']),
                ],
            ])
            ->add('table_number', TextType::class, [
                'label' => 'Preferred Table (optional)',
                'required' => false,
            ])
            ->add('special_requests', TextareaType::class, [
                'label' => 'Special Requests',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

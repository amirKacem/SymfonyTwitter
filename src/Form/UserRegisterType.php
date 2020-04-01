<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,
                ['required'=>true])
           ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
               'empty_data' => '',

                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],

            ])
            ->add('firstname',TextType::class,
                ['required' => true,  'error_bubbling' => true])
            ->add('lastname',TextType::class,
                ['required' => true])
            ->add('email',EmailType::class,[
                'required' => false
            ])
            ->add('Profile', ProfileFormType::class, [
                'data_class' => Profile::class,
            ]);





    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,

        ]);
    }
}

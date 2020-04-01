<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('short_presentation',TextareaType::class,
                ['required'=>false,'attr'=>['maxlength'=>255]])
            ->add('description',TextareaType::class,
                ['required'=>false])
            ->add('profil_img',FileType::class,
                ['required'=>false,
                    'attr' => ['class' => 'form-control-file mt-2 p-2','style'=>"opacity:1"],
                    'label'=>"Image Profil ",
                    'mapped'=>false]
                    )
            ->add('couverture_img',FileType::class,
                ['required'=>false,
                    'attr' => ['class' => 'form-control-file mt-2 p-2','style'=>"opacity:1"],
                    'label'=>"Image Couverture ",
                    'mapped'=>false])
            ->add('pays',CountryType::class,
                ['required'=>false,
                    'mapped'=>false]);

    }


}

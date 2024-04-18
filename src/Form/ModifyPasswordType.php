<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\{SubmitType,PasswordType,RepeatedType}; 

use App\Classe\NewPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $requis = true;
        $builder
            ->add('oldpassword', PasswordType::class)
            ->add('newpassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent etre identique.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Nouveau Mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
            ])
            ->add('confirmer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewPassword::class,
        ]);
    }
}

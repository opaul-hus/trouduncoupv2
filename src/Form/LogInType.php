<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType,PasswordType}; 
use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $requis = true;
        $builder
            ->add('username', TextType::class,array('required' => $requis))
            ->add('password', PasswordType::class,array('required' => $requis))
            ->add('connexion', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}

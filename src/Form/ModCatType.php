<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType}; 
class ModCatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       

        $builder
            ->add('nom', TextType::class, array('label' => false , 'required' => true))
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}

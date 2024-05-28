<?php

namespace App\Form;

use App\Classe\CategoriesList;
use Symfony\Component\Form\Extension\Core\Type\{SubmitType,CollectionType}; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModAllCatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('categories', CollectionType::class,
            [
                'entry_type' => ModCatType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);

            $builder->add('Modifier', SubmitType::class);
            



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       
            $resolver->setDefaults([
                'data_class' => CategoriesList::class,
           
        ]);
    }
}

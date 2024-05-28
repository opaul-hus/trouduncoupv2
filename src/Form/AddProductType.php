<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType, MoneyType, IntegerType};

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, array('label' => 'Nom du produit' , 'required' => true))
            ->add('prix',MoneyType::class, array('label' => 'Prix du produit' , 'required' => true))
            ->add('description',TextType::class, array('label' => 'Description du produit' , 'required' => true))
            ->add('quanStock',IntegerType::class, array('label' => 'Quantité en stock' , 'required' => true))
            ->add('quanMin', IntegerType::class, array('label' => 'Quantité minimale' , 'required' => true))
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
'choice_label' => 'nom',
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}

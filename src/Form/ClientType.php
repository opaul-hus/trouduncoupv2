<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType,EmailType,PasswordType,ChoiceType,RepeatedType}; 
use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

use Doctrine\Persistence\ManagerRegistry;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $requis = true;
        $builder
            ->add('username', TextType::class,array('required' => $requis))
            ->add('prenom', TextType::class,array('required' => $requis))
            ->add('nom', TextType::class,array('required' => $requis))
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F',
                    'Autre' => 'A',
                ],
            ],
            array('required' => $requis))
            ->add('adresse', TextType::class,array('required' => $requis))
            ->add('ville', TextType::class,array('required' => $requis))
            ->add('province', ChoiceType::class, [
                'choices' => [
                    'Alberta' => 'AB',
                    'Colombie-Britannique' => 'BC',
                    'Île-du-Prince-Édouard' => 'PE',
                    'Manitoba' => 'MB',
                    'Nouveau-Brunswick' => 'NB',
                    'Nouvelle-Écosse' => 'NS',
                    'Ontario' => 'ON',
                    'Québec' => 'QC',
                    'Saskatchewan' => 'SK',
                    'Terre-Neuve-et-Labrador' => 'NL',
                    'Territoires du Nord-Ouest' => 'NT',
                    'Yukon' => 'YT',
                    'Nunavut' => 'NU'
                ],
            ],array('required' => $requis))
            ->add('codePostal', TextType::class,array('required' => $requis))
            ->add('numeroTel', TextType::class,array('required' => $requis))
            ->add('email', EmailType::class,array('required' => $requis))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent etre identique.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer mot de passe'],
            ])
            ->add('sauvegarder', SubmitType::class)
        ;

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void {

                $form = $event->getForm();
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                if ($this->dbContains($data['username'])) {
                    # code...
                    $form->addError(new FormError('Ce nom d\'utilisateur est déjà utilisé'));
                }
                $data['codePostal'] = $this->convertPostalCode($data['codePostal']);
                $data['numeroTel'] = $this->convertPhoneNumber($data['numeroTel']);
            
                $event->setData($data);
    });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }


    private function convertPostalCode($codePostal)
    {
        $codePostal = strtoupper($codePostal);
        $codePostal = str_replace(' ', '', $codePostal);
        $codePostal = substr($codePostal, 0, 3) . ' ' . substr($codePostal, 3);
        return $codePostal;
    }

    private function convertPhoneNumber($numeroTel)
    {
        $numeroTel = str_replace(' ', '', $numeroTel);
        $numeroTel = str_replace('-', '', $numeroTel);
        $numeroTel = str_replace('(', '', $numeroTel);
        $numeroTel = str_replace(')', '', $numeroTel);
        $numeroTel = str_replace('.', '', $numeroTel);
        $numeroTel = str_replace('+', '', $numeroTel);
        return $numeroTel;
    }

    private function dbContains($username,ManagerRegistry $doctrine)
    {
        $compte = $doctrine
            ->getRepository(Compte::class)
            ->findOneBy(['username' => $username]);

        if ($compte) return true;
        return false;
    }

}

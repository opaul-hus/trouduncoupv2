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
use Symfony\Component\HttpFoundation\RequestStack;

use Doctrine\ORM\EntityManagerInterface;

class ClientType extends AbstractType
{

    
    private $em;

    public function __construct(EntityManagerInterface $entityManager,private RequestStack $requestStack) {
        $this->em = $entityManager;
        
    }
  
    //-----------------------------------------------------------------------------
    //fonction qui construit le formulaire
    //on ajoute les champs du formulaire
    //on ajoute un evenement pour verifier si le nom d'utilisateur est deja utilise
    //on ajoute un evenement pour formater le code postal et le numero de telephone
    //-----------------------------------------------------------------------------
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
                $data = $event->getData();

                if (!$this->requestStack->getSession()->has('compte_connecte')&&$this->dbContains($data['username'])) {
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

 
    

    //-----------------------------------------------------------------------------
    //fonction qui formate le code postal
    //on enleve les espaces et on ajoute un espace entre les 3 premiers caracteres et les 3 derniers
    //-----------------------------------------------------------------------------
    private function convertPostalCode($codePostal)
    {
        $codePostal = strtoupper($codePostal);
        $codePostal = str_replace(' ', '', $codePostal);
        $codePostal = substr($codePostal, 0, 3) . ' ' . substr($codePostal, 3);
        return $codePostal;
    }

    //-----------------------------------------------------------------------------
    //fonction qui formate le numero de telephone
    //on enleve les espaces, les tirets, les parentheses, les points et le signe plus
    //-----------------------------------------------------------------------------
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

    //-----------------------------------------------------------------------------
    //fonction qui verifie si le nom d'utilisateur est deja dans la base de donnees
    //-----------------------------------------------------------------------------
    private function dbContains($username)
    {
        
        $compte = $this->em->getRepository(Compte::class)->findOneBy(['username' => $username]);
            

        if ($compte) return true;
        return false;
    }

}

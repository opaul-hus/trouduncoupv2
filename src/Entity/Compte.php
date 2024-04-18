<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $username = null;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern:'/^\S{2,15}$/i', match:true, message:'La taille ou le contenu non conforme')]
    private ?string $prenom = null;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern:'/^\S{2,15}$/i', match:true, message:'La taille ou le contenu non conforme')]
    private ?string $nom = null;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(choices: ['H', 'F', 'A'], message: 'Choisir un genre valide .')]
    private ?string $genre = null;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern: '/^\d+.+\S/', match: true, message: 'L\'adrersse doit commencer avec un chiffre suivi du nom de la rue.')]
    private ?string $adresse = null;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern:'/^\S{2,15}$/i', match:true, message:'La taille ou le contenu non conforme')]
    private ?string $ville = null;

    #[ORM\Column(length: 15)]
    #[Assert\Choice(choices: ['AB', 'BC', 'PE', 'MB', 'NB', 'NS', 'ON', 'QC', 'SK', 'NL', 'NT', 'YT', 'NU'], message: 'Choisir une province valid .')]
    private ?string $province = null;

    #[ORM\Column(length: 7)]
    #[Assert\Regex(pattern: '/^[ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVWXYZ] [0-9][ABCEGHJKLMNPRSTVWXYZ][0-9]$/', match: true, message: 'Code postal non valide.')]
    private ?string $codePostal = null;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern:'/^[0-9]{10}$/i', match:true, message:'La taille ou le contenu non conforme')]
    private ?string $numeroTel = null;

    #[ORM\Column(length: 25)]
    #[Assert\Email(message: 'Email non valide.')]
    private ?string $email = null;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern:'/^.{2,15}$/i', match:true, message:'La taille ou le contenu non conforme')]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(string $numeroTel): static
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}

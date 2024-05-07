<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


  

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Compte $client = null;
    
        /**
     * @var Collection<int, CommandeDetail>
     */
    #[ORM\OneToMany(targetEntity: CommandeDetail::class, mappedBy: 'commande',cascade: ['persist', 'remove'])]
    private Collection $commandeDetail;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;


    

    public function __construct()
    {
        $this->commandeDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getClient(): ?Compte
    {
        return $this->client;
    }

    public function setClient(?Compte $client): static
    {
        $this->client = $client;

        return $this;
    }

        /**
     * @return Collection<int, CommandeDetail>
     */
    public function getCommandeDetail(): Collection
    {
        return $this->commandeDetail;
    }

    public function addCommandeDetail(CommandeDetail $commandeDetail): static
    {
        if (!$this->commandeDetail->contains($commandeDetail)) {
            $this->commandeDetail->add($commandeDetail);
            $commandeDetail->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetail $commandeDetail): static
    {
        if ($this->commandeDetail->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getCommande() === $this) {
                $commandeDetail->setCommande(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    //------------------------
    //fonction pour calculer le total de la commande
    //------------------------
    public function getTotal(): ?float
    {
        $total = 0;

        foreach ($this->commandeDetail as $commandeDetail) {
            $total += $commandeDetail->getProduits()->getPrix() * $commandeDetail->getQuantite();
        }
        $total +=9.99;  
        $total*=1.05;
        $total*=1.0975;


        return $total;
    }

    //------------------------
    //fonction pour verifier si la commande est annulable
    //------------------------
    public function cancelable(): bool
    {
        $now = new \DateTime();
        $diff = $now->diff($this->date);
        if ($diff->days >= 2) {
            return false;
        }

        return true;
    }

    //------------------------
    //fonction pour afficher le temps restant pour annuler la commande
    //------------------------
    public function getCountDown(): string{

        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone('America/New_York'));
        $timeLimit=$this->date->add(new \DateInterval('P2D'));
        $timeLimit->setTimezone(new \DateTimeZone('America/New_York'));
        $diff = $now->diff($timeLimit);
       
        if($diff->d>=1){
            if ($diff->h<=1) {
                return $diff->format('Il vous reste %d jour et %h heure pour annuler votre commande');
            }
            return $diff->format('Il vous reste %d jour et %h heures pour annuler votre commande');
        }
        else{
           
            if ($diff->h>=1) {

                if ($diff->h<=1&&$diff->i<=1) {
                    return $diff->format('Il vous reste %h heure et %i minute pour annuler votre commande');
                }
                else if ($diff->i<=1) {
                    return $diff->format('Il vous reste %h heures et %i minute pour annuler votre commande');
                }
                else if ($diff->h<=1) {
                    return $diff->format('Il vous reste %h heure et %i minutes pour annuler votre commande');
                }
                else{
                    return $diff->format('Il vous reste %h heures et %i minutes pour annuler votre commande');
                }
            

            }
            else{
                dd($diff);
                if ($diff->i<=1&&$diff->s<=1) {
                    return $diff->format('Il vous reste %i minute et %s seconde pour annuler votre commande');
                }
                else if ($diff->i<=1) {
                    return $diff->format('Il vous reste %i minute et %s  pour annuler votre commande');
                }
                else if ($diff->s<=1) {
                    return $diff->format('Il vous reste %i minutes et %s secondes pour annuler votre commande');
                }
                else{
                    return $diff->format('Il vous reste %i minutes et %s secondes pour annuler votre commande');
                }
            
                
                
            }
            
        }
    



}
}
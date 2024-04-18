<?php



namespace App\Classe;
use Symfony\Component\Validator\Constraints as Assert;

//--------------------------------------
//classe qui permet de stocker les nouveaux mots de passe
//et de les valider
//--------------------------------------
class NewPassword{
#[Assert\Regex(pattern:'/^.{2,15}$/i', match:true, message:'La taille ou le contenu non conforme')]
private  $oldPassword="";
#[Assert\Regex(pattern:'/^.{2,15}$/i', match:true, message:'La taille ou le contenu non conforme')]
private  $newPassword="";

public function getOldPassword(): string
{
    return $this->oldPassword;
}
public function setOldPassword(string $oldPassword): static
{
    $this->oldPassword = $oldPassword;

    return $this;
}
public function getNewPassword(): string
{
    return $this->newPassword;

}
public function setNewPassword(string $newPassword): static
{
    $this->newPassword = $newPassword;

    return $this;
}

}

<?php 
// ..\src\Classe\PhotoChomeur.php 
namespace App\Classe; 
use Symfony\Component\HttpFoundation\File\UploadedFile; 
class PhotoProduit
{ 
 private $idProduit; 
 private $imageProduit; 
 
 public function getImageProduit() : ?UploadedFile 
 { 
 return $this->imageProduit; 
 } 
 public function setImageProduit (UploadedFile $fichier = null) 
 { 
 $this->imageProduit = $fichier; 
 } 
 public function getProduitId() { return $this->idProduit; } 
 public function setProduitId($id) {$this->idProduit= $id;} 

     public function televerse(&$codeErreur = 0, $principale )
    {
        $type = $this->imageProduit->getClientMimeType();
        if ($type == 'image/gif'  ||
            $type == 'image/png' ||
            $type == 'image/jpeg' )
         {
            // on construit le dossier de destinatrion
            $nomDossier = __DIR__ . '/../../public/css/images';
            // on construit le nom du fichier
            if ($principale)
            {
                $nomFichier = "$this->idProduit.jpg";
            }
               
            else
            {
                $nomFichier = "$this->idProduit" . "_2.jpg";
            }
                
            

            // on move le ficheir reçu dans son dossier final
            $this->imageProduit->move($nomDossier, $nomFichier);
            return true;
         } 
         else
         {
            $codeErreur = -3;
            return false;
         }  
} 
}

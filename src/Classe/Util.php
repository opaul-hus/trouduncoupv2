<?php
namespace App\Classe;

use Symfony\Component\HttpFoundation\Request;

 
class Util
{
    public static function HeureLocale()
    {
       ini_set("date.timezone", 'america/new_york');
    }

    public static function Secure(Request $req ,$typeCompte='CONNECTE')
    
    {

        if ($typeCompte == 'CONNECTE')
        {
            $entiteConnectee = $req->getSession()->get('compte_connecte');
        }
        else
        {
            $entiteConnectee = $req->getSession()->get('client_possible');

        }
       
        
        
  
        if (empty($entiteConnectee))
        {
            return false;
        }
        return true;
    }
}
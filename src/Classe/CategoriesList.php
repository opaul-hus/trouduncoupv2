<?php



namespace App\Classe;


use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Categories;


class CategoriesList{

    private $categories = [];


    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): static
    {
        $this->categories = $categories;
       


        return $this;
    }



}

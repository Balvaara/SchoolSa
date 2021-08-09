<?php

namespace App\Generateur;

use App\Repository\InscrireRepository;



class GenererNumIns{

    private $numero;


    public function __construct(InscrireRepository $insrip)
    {
      

        $last=$insrip->findOneBy([],['id'=>'desc']);

        if ($last!=null) {

            $lastId=$last->getId();

            $this->numero=sprintf("%'.0d",$lastId +1);
        }
        else{
            $this->numero=sprintf("%'.0d",1);
        }

    }

    public function generer(){
    
        return $this->numero;
    }
}
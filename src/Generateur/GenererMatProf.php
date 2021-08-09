<?php

namespace App\Generateur;


use App\Repository\ProfesseurRepository;


class GenererMatProf{

    private $numero;


    public function __construct(ProfesseurRepository $ProfesseurRepository)
    {
      

        $last=$ProfesseurRepository->findOneBy([],['id'=>'desc']);

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
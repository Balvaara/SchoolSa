<?php

namespace App\Generateur;


use App\Repository\PayementRepository;


class GenererNumPayement{

    private $numero;


    public function __construct(PayementRepository $PayementRepository)
    {
      

        $last=$PayementRepository->findOneBy([],['id'=>'desc']);

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
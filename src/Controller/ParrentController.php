<?php

namespace App\Controller;

use App\Entity\Parrent;
use App\Entity\Professeur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route( "/api" )
*/
class ParrentController extends AbstractController
{
    /**
     * @Route("/modifparrent/{id}", name="modifier_parrent",  methods={"PUT"})
     */
    public function index($id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $contenu =json_decode($request->getContent());
        if(isset($contenu->nom,$contenu->prenom,$contenu->adresse,$contenu->telephone))
        {
            $recuperation = $this->getDoctrine()->getRepository(Parrent::class);
            $le_tuteur= $recuperation->find($id);
            
            if($le_tuteur)
            {
            // On instancie une nouvelle ressource.
            $parrent = $le_tuteur;
            //dd($ele);
                $parrent->setPrenomp($contenu->prenom);
                $parrent->setNomp($contenu->nom);
                $parrent->setAdressep($contenu->adresse);
                $parrent->setTelp($contenu->telephone);
               
                //ajouter dans la base de donne.
                $entityManager->persist($parrent);
                $entityManager->flush(); 
                $data = [
                    'status' => 201,
                    'message' => 'le parrent est modifier avec succes.'
                ];
                return new JsonResponse($data, 201); 
            } 
           
        }
        $data = [
            'status' => 500,
            'message' => "echec du modification."
        ];
         return new JsonResponse($data, 500);
    }

            /**
             *@Route("/nbPro", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Professeur::class);
                $classe = $repo->findAll();
               
                $somme=0;
             
                $ucsercon =$this->getUser();
                //dd($ucsercon);
                
                    foreach($classe as $cl)
                    {
                           
                            $somme++;
                        
                    }

                    //  dd($somme);
                    
                
                
                return $this->json($somme, 201);
            }
}

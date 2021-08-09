<?php

namespace App\Controller;

use App\Entity\Serie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class SerieController extends AbstractController
{

    /**
     * @Route("/addserie", name="serie" ,methods={"POST"})
     */
    
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $serie =$serializer->deserialize($request->getContent(), Serie::class, 'json');
        $errors = $validator->validate($serie);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
            
            $users = $this->getUser();
        if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
             //pour convertire le champ libelle en majuscule
            $serie->setLibelleserie(strtoupper($serie->getLibelleserie()));
            $serie->setCodeserie($serie->getCodeserie());
            $entityManager->persist($serie);
            $entityManager->flush();
            
            $data = [
                'status' => 201,
                'message' => 'serie '.$serie->getLibelleserie().' Ajoutée'
            ];
            return new JsonResponse($data, 200);
        }
        
            $data = [
                'status' => 201,
                'message' => 'vous n\'avez pas les autorisations'
            ];
            return new JsonResponse($data, 200);
    }

                         //suppression
    /**
    * @Route("/deleteSerie/{id}", name="deleteSerie", methods={"DELETE"})
    */
    public function delete($id)
    {
                
        $rep = $this->getDoctrine()->getRepository(Serie::class);
        $status='';
        $serie=$rep->find($id);
        $users = $this->getUser();
           if ($users->getRoles()==["ROLE_DIRECTEUR"]||$users->getRoles()==["ROLE_SECRETAIRE"]) {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($serie);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'La serie a été Supprimée avec succès !!!'
                    ];
                    return new JsonResponse($data, 200);
                }
                $data = [
                    'status' => 200,
                    'message' => 'Vous n\'avez pas les autorisations'
                    ];
                    return new JsonResponse($data, 200);
    }
                            //modification
    /**
    * @Route("/editeSerie/{id}", name="editeSerie", methods={"PUT"})
    */
    public function update($id,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $serieMod = $entityManager->getRepository(Serie::class)->find($id);
                
        $data =json_decode($request->getContent()); 
        $errors = $validator->validate($serieMod);
            if(count($errors)) {
                    $errors = $serializer->serialize($errors, 'json');
                    return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $users = $this->getUser();
              if ($users->getRoles()==["ROLE_DIRECTEUR"]||$users->getRoles()==["ROLE_SECRETAIRE"]) {
                $serieMod->setCodeserie(strtoupper($data->codeserie));
                $serieMod->setLibelleserie(strtoupper($data->libelleserie));
                $entityManager->persist($serieMod);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Serie Modifiée avec succès'
                ];
                return new JsonResponse($data);
            }
            $data = [
                'status' => 200,
                'message' => 'vous n\'avez pas les autorisatios pour modifier les series'
            ];
            return new JsonResponse($data);
    }
                           //Liste des serie
    /**
     * @Route("/listeSerie", name="listeSerie" )
     */
    public function liste(){
        $list=$this->getDoctrine()->getRepository(Serie::class);
        $val=$list->findAll();      
        return $this->json($val);
    }


            /**
             *@Route("/nbSerie", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Serie::class);
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

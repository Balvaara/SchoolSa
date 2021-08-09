<?php

namespace App\Controller;

use App\Entity\Classe;
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
class ClasseController extends AbstractController
{
                    
    /**
     * ajout une classe
     * @Route("/addclasse", name="classe" ,methods={"POST"})
     */
    
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $classe =$serializer->deserialize($request->getContent(), Classe::class, 'json');
        $errors = $validator->validate($classe);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
            $users = $this->getUser();
        if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
            $classe->setCodeclasse(strtoupper($classe->getCodeclasse()));
            $classe->setLibelleclasse(strtoupper($classe->getLibelleclasse()));
            // $classe->setMontantIns($classe->getMontantIns());
            // $classe->setMontantMens($classe->getMontantMens());
            $entityManager->persist($classe);
            $entityManager->flush();
            
            $data = [
                'status' => 201,
                'message' => 'classe inserée'
            ];
            return new JsonResponse($data, 200);
        }
        
            $data = [
                'status' => 201,
                'message' => 'vous n\'avez pas les autorisations'
            ];
            return new JsonResponse($data, 200);
    }
                        //suppression classe
    /**
    * @Route("/deleteClasse/{id}", name="deleteClasse", methods={"DELETE"})
    */
    public function delete($id)
    {
        
        $repons = $this->getDoctrine()->getRepository(Classe::class);
        $status='';
        $classe=$repons->find($id);
        $users = $this->getUser();
           if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($classe);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Classe Supprimée avec succès'
                    ];
                    return new JsonResponse($data, 200);
            }
                $data = [
                    'status' => 200,
                    'message' => 'Vous n\'avez pas les autorisations'
                    ];
                    return new JsonResponse($data, 200);
    }
              //modification classe
    /**
    * @Route("/editeClasse/{id}", name="editeClasse", methods={"PUT"})
    */
    public function update($id,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
        {
            $classeMod = $entityManager->getRepository(Classe::class)->find($id);
                
            $data =json_decode($request->getContent());
       
            $classe =$serializer->deserialize($request->getContent(), Classe::class, 'json');
            $errors = $validator->validate($classeMod);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                 return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]); 
                }
                $users = $this->getUser();
            if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $classeMod->setCodeclasse(strtoupper($data->codeclasse));
                $classeMod->setLibelleclasse(strtoupper($data->libelleclasse));
                $classeMod->setMontantIns($data->montantIns);
                $classeMod->setMontantMens($data->montantMens);
                $classeMod->setNiveaux($classe->getNiveaux());
                $classeMod->setSeries($classe->getSeries());
                $entityManager->persist($classeMod);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'classe Modifiée avec succès'
                ];
                return new JsonResponse($data);
            }
            $data = [
                'status' => 200,
                'message' => 'vous n\'avez pas les autorisations'
            ];
            return new JsonResponse($data);

        }
                   //Liste des classe
    /**
     * @Route("/listeClasse", name="listeClasse" )
     */
    public function liste()
    {
        $list=$this->getDoctrine()->getRepository(Classe::class);
        $val=$list->findAll();
        
        return $this->json($val);
    }  


            /**
             *@Route("/nbClasse", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Classe::class);
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

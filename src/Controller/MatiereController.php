<?php

namespace App\Controller;

use App\Entity\ClasseMatiere;
use App\Entity\Note;
use App\Entity\Devoir;
use App\Entity\Matiere;
use Doctrine\DBAL\Tools\Dumper;
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
class MatiereController extends AbstractController
{
                    //ajout Niveau
    /**
     * @Route("/addMatiere", name="Matiere" ,methods={"POST"})
     */
    
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $Matiere =$serializer->deserialize($request->getContent(), Matiere::class, 'json');

        $errors = $validator->validate($Matiere);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
            $users = $this->getUser();
        if ($users->getRoles()==["ROLE_DIRECTEUR"]) {

            //insertion d'une matiere
            $Matiere->setLibellemat($Matiere->getLibellemat());
            $entityManager->persist($Matiere);
            $entityManager->flush();
            
            
            $data = [
                'status' => 201,
                'message' => 'Matiere inserée'
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
    * @Route("/deleteMatiere/{id}", name="deleteMatiere", methods={"DELETE"})
    */
    public function delete($id)
    {
       
        $rep = $this->getDoctrine()->getRepository(Matiere::class);
                $status='';
                $Matiere=$rep->find($id);
                $users = $this->getUser();
           if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($Matiere);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Matiere Supprimée avec succès !!!'
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
    * @Route("/editeMatiere/{id}", name="editeMatiere", methods={"PUT"})
    */
    public function update($id,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
        {
            $MatiereMod = $entityManager->getRepository(Matiere::class)->find($id);
                
            $data =json_decode($request->getContent());
       
            $Matiere =$serializer->deserialize($request->getContent(), Matiere::class, 'json');
            $errors = $validator->validate($MatiereMod);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                 return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $users = $this->getUser();
            if ($users->getRoles()==["ROLE_DIRECTEUR"]) {

                $MatiereMod->setLibellemat($data->libellemat);
                $entityManager->persist($MatiereMod);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Matiere Modifiée avec succès'
                ];
                return new JsonResponse($data);
            }
            $data = [
                'status' => 200,
                'message' => 'vous n\'avez pas les autorisations'
            ];
            return new JsonResponse($data);

        }
                        //Liste des matiere
    /**
     * @Route("/listeMatiere", name="listeMatiere" )
     */
    public function liste(){
        $list=$this->getDoctrine()->getRepository(Matiere::class);
        $val=$list->findAll();
        
        return $this->json($val);
    }  
    
             /**
             *@Route("/nbMat", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Matiere::class);
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

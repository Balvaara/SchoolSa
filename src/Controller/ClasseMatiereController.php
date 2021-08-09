<?php

namespace App\Controller;

use App\Entity\ClasseMatiere;
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
class ClasseMatiereController extends AbstractController
{
                  
    /**
     * ajout une matiere dans une classe
     * @Route("/addClMatiere" ,methods={"POST"})
     */
    
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $cl_mt =$serializer->deserialize($request->getContent(), ClasseMatiere::class, 'json');

        $errors = $validator->validate($cl_mt);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
            $users = $this->getUser();
        if ($users->getRoles()==["ROLE_DIRECTEUR"]) {

            $entityManager->persist($cl_mt);
            $entityManager->flush();
            
            $data = [
                'status' => 201,
                'message' => 'Matiere '.$cl_mt->getMatieres()->getLibellemat().' ajoutée dans la classe '
                .$cl_mt->getClasses()->getLibelleclasse()
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
    * @Route("/deleteClMatiere/{id}",  methods={"DELETE"})
    */
    public function delete($id)
    {
       
        $rep = $this->getDoctrine()->getRepository(ClasseMatiere::class);
                $status='';
                $Matiere=$rep->find($id);
                $users = $this->getUser();
           if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($Matiere);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Supprimée avec succès !!!'
                    ];
                    return new JsonResponse($data, 200);
                }
                $data = [
                    'status' => 200,
                    'message' => 'Vous n\'avez pas les autorisations'
                    ];
                    return new JsonResponse($data, 200);
    }
             
    /**
     * modifier une matiere pour une classe
    * @Route("/editeClMatiere/{id}", methods={"PUT"})
    */
    public function update($id,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
        {
            $MatiereMod = $entityManager->getRepository(ClasseMatiere::class)->find($id);
            $cl_mt =$serializer->deserialize($request->getContent(), ClasseMatiere::class, 'json');
                
            $data =json_decode($request->getContent());
       
           
            $errors = $validator->validate($MatiereMod);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                 return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $users = $this->getUser();
            if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
               
                $MatiereMod->setCoef($cl_mt->getCoef());
                $entityManager->persist($MatiereMod);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => ' Modifiée avec succès'
                ];
                return new JsonResponse($data);
            }
            $data = [
                'status' => 200,
                'message' => 'vous n\'avez pas les autorisations'
            ];
            return new JsonResponse($data);

        }
                        
    /**
     * @Route("/listeClMatiere",)
     */
    public function liste(){
        $list=$this->getDoctrine()->getRepository(ClasseMatiere::class);
        $val=$list->findAll();
        
        return $this->json($val);
    }  
    
             /**
             *@Route("/nbClMat", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(ClasseMatiere::class);
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

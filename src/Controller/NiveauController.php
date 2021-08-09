<?php

namespace App\Controller;

use App\Entity\Niveau;
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

class NiveauController extends AbstractController
{


                   //ajout Niveau
    /**
     * @Route("/addNiveau", name="niveau" ,methods={"POST"})
     */
    
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $niveau =$serializer->deserialize($request->getContent(), Niveau::class, 'json');
        $errors = $validator->validate($niveau);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
            $users = $this->getUser();
        if ($users->getRoles()==["ROLE_DIRECTEUR"]) {

            $niveau->setLibelleniveau(strtoupper($niveau->getLibelleniveau()));
            $entityManager->persist($niveau);
            $entityManager->flush();
            
            $data = [
                'status' => 201,
                'message' => 'Niveau inseré'
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
    * @Route("/deleteNiveau/{id}", name="deleteNiveau", methods={"DELETE"})
    */
    public function delete($id)
    {
        // $users = $this->getUser();

        $rep = $this->getDoctrine()->getRepository(Niveau::class);
                $status='';
                $niveau=$rep->find($id);
                
                $users = $this->getUser();
           if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
            // dd($niveau);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($niveau);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Le niveau est Supprimé avec succès !!!'
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
    * @Route("/editeNiveau/{id}", name="editeNiveau", methods={"PUT"})
    */
    public function update($id,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $niveauMod = $entityManager->getRepository(Niveau::class)->find($id);
                
        $data =json_decode($request->getContent());       
        $errors = $validator->validate($niveauMod);
                if(count($errors)) {
                    $errors = $serializer->serialize($errors, 'json');
                    return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $users = $this->getUser();
              if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $niveauMod->setLibelleniveau(strtoupper($data->libelleniveau));
                $entityManager->persist($niveauMod);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Niveau Modifié avec succès'
                ];
                return new JsonResponse($data);
            }
            $data = [
                'status' => 200,
                'message' => 'vous n\'avez pas les autorisations de modifier '.$niveauMod->getLibelleniveau(). ''
            ];
            return new JsonResponse($data);
    }
               //Liste
    /**
     * @Route("/listeNiveau", name="listeNiveau" )
     */
    public function liste(){
        
        $i= 0;
        $list=$this->getDoctrine()->getRepository(Niveau::class);
        $val=$list->findAll();
        
        return $this->json($val);
    }

            /**
             *@Route("/nbNiv", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Niveau::class);
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

           
                   
        

       
        

    



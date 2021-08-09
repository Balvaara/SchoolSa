<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/user", name="add_user", methods={"POST"})
     */

    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        
        // $users = $this->getUser();

        $user =$serializer->deserialize($request->getContent(), User::class, 'json');

        $users = $this->getUser();
        // $this-> denyAccessUnlessGranted(['ROLE_SUP_ADMIN','ROLE_ADMIN','ROLE_PARTENAIRE']);

        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
            }
      
            if ($users->getRoles()==["ROLE_DIRECTEUR"] || $users->getRoles()==["ROLE_SECRETAIRE"]) {
            
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $user->setRoles(["ROLE_".strtoupper($user->getProfil()->getLibelleRole())]);
            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Utilisateur '.$user->getNomComplet().' Ajouté(e) avec succès'
            ];
            return new JsonResponse($data, 200);
        }

        $data = [
            'status' => 201,
            'message' => 'vous n\'avez pas les autorisations'
        ];
        return new JsonResponse($data, 200);
    }

            /**
             * @Route("/users/status/{id}", methods={"GET"})
            */
            public function EtatUse($id)
            {
                   
        
                $rep = $this->getDoctrine()->getRepository(User::class);
                $status='';
                $user=$rep->find($id);
                $users = $this->getUser();
            if ($users->getProfil()->getLibelleRole()=="DIRECTEUR")
            {
                if ($user->getIsActive()==true) 
                {
                
                    $user->setIsActive(false);
                        $status=' Bloqué';
                   
                            
                 }
               else{
                  $user->setIsActive(true);
                      $status='Debloqué';
                 }
                        
                     $entityManager=$this->getDoctrine()->getManager();
                     $entityManager->persist($user);
                     $entityManager->flush();
        
                        $data = [
                        'status' => 201,
                        'message' =>'L\'utilisateur '. $user->getUsername().' est '.$status
                            ];
                        return new JsonResponse($data, 200);
                            
                }else{
                    $data = [
                        'status' => 401,
                        'message' => 'Vous n\'avez pas les autorisation nessessaires veuillez vous rapprochez de Votre Administrateurs Svp' 
                    ];
                     return new JsonResponse($data, 200);
        
                }
            }

             /**
             * @Route("/delete/{id}", name="delete", methods={"DELETE"})
             */
            public function delete($id)
            {
                $users = $this->getUser();

                $rep = $this->getDoctrine()->getRepository(User::class);
                $status='';
                $user=$rep->find($id);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Utilisateur Supprimé(e) avec succès !!!'
                    ];
                    return new JsonResponse($data, 200);
            }


             /**
             * @Route("/edite/{id}", name="edite", methods={"PUT"})
             */
            public function update($id,Request $request,UserPasswordEncoderInterface $encoder, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
            {
                $userUpdate = $entityManager->getRepository(User::class)->find($id);
                
                $data =json_decode($request->getContent());
                

                
        //    dd($data);
                $user =$serializer->deserialize($request->getContent(), User::class, 'json');

                $errors = $validator->validate($userUpdate);
                if(count($errors)) {
                    $errors = $serializer->serialize($errors, 'json');
                    return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $userUpdate->setUsername($data->username);
                $userUpdate->setPassword($this->encoder->encodePassword($userUpdate, $data->password));
                $userUpdate->setRoles(["ROLE_".strtoupper($userUpdate->getProfil()->getLibelleRole())]);
                $userUpdate->setProfil($user->getProfil());
                $userUpdate->setNomComplet($data->nomComplet);
                $userUpdate->setIsActive($data->isActive);


                $entityManager->persist($userUpdate);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Utilisateur Modifié(e) avec succès'
                ];
                return new JsonResponse($data);
            }

            /**
             *@Route("/listerUsers", methods={"GET"})
            */
            public function getUsers()
            {
                
                $repo = $this->getDoctrine()->getRepository(User::class);
                $users = $repo->findAll();
               
                
                $data = [];
                $i= 0;
                $ucsercon =$this->getUser();
                //dd($ucsercon);
                if($ucsercon->getProfil()->getLibelleRole()  ==="DIRECTEUR")
                {
                    foreach($users as $user)
                    {
                        if($user->getProfil()->getLibelleRole() === 'SECRETAIRE' || $user->getProfil()->getLibelleRole() === 'TRESORIER'
                        || $user->getProfil()->getLibelleRole() === 'SURVEILLANT' )
                        {

                            $data[$i]=$user;
                            $i++;
                        }
                        
                    }
                }
                elseif($ucsercon->getProfil()->getLibelleRole()  ===  "SECRETAIRE")
                {
                    
                    foreach($users as $user)
                    {
                        if($user->getProfil()->getLibelleRole() === "TRESORIER" ||
                         $user->getProfil()->getLibelleRole() === "SURVEILLANT")
                        {
                           
                        $data[$i]=$user;
                        $i++;
                        
                    }
                        
                    }
                }
             
                else
                {
                    $data = [
                        'status' => 401,
                        'message' => 'Désolé access non autorisé !!!'
                        ];
                        return new JsonResponse($data, 401);
                    
                }
                return $this->json($data, 201);
            }

	
        /**
             * @Route("/users/id/{id}",  name=" getUser" ,methods={"GET"})
             */
            public function getById($id, EntityManagerInterface $entityManager)
            {
                $userUpdate = $entityManager->getRepository(User::class)->find($id);
                
                
                
                return $this->json($userUpdate, 200);
        //    
            }

             /**
             * @Route("/editeprofil/{id}")
             */
            public function EditePro($id,Request $request, EntityManagerInterface $entityManager)
            {
                $data =json_decode($request->getContent());
                $ucsercon =$this->getUser();
                $userUpdate = $entityManager->getRepository(User::class)->find($ucsercon->getId());
                
                
                $userUpdate->setUsername($data->username);
                $userUpdate->setNomComplet($data->nomComplet);
                $userUpdate->setPassword($this->encoder->encodePassword($userUpdate, $data->password));
                $entityManager->persist($userUpdate);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Profil Modifié avec succès'
                ];
                return new JsonResponse($data);
        //    
            }
    
            /**
             * @Route("/userCon",methods={"GET"})
             */
            public function getCon()
            {
               
                $data=[];
                $i=0;
                $ucsercon =$this->getUser();
                $rep = $this->getDoctrine()->getRepository(User::class);
                $user=$rep->findAll();
                foreach ($user as  $value)
                {
                    if ($ucsercon->getId()===$value->getId()) {
                        $data[$i]=$ucsercon;
                        $i++;
                       
                    }
                    
                
                }
                return $this->json($data, 200);
        }



            /**
             *@Route("/usersCompt", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(User::class);
                $pro = $repo->findAll();
                $somme=count($pro);
                return $this->json($somme, 201);
            }
        

    
}

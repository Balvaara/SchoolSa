<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Inscrire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class RoleController extends AbstractController
{
            /**
             * @Route("/listerRoles", methods={"GET"})
            */
            public function getRoles(Request $request, EntityManagerInterface $entityManager)
            {
                
                $repo = $this->getDoctrine()->getRepository(Role::class);
                $roles = $repo->findAll();
                
                $data = [];
                $i= 0;
                $rolesUser =$this->getUser();
                //dd($rolesUser);
                if($rolesUser->getRoles() ==  ["ROLE_DIRECTEUR"])
                {
                    foreach($roles as $role)
                    {
                        if($role->getLibelleRole() === 'SECRETAIRE' || 
                        $role->getLibelleRole() === 'TRESORIER'
                        || $role->getLibelleRole() === 'SURVEILLANT')
                        {
                            $data[$i]=$role;
                            $i++;
                        }
                        
                    }
                }
                elseif($rolesUser->getRoles() ===  ["ROLE_SECRETAIRE"])
                {
                    
                    foreach($roles as $role)
                    {
                        if($role->getLibelleRole() === 'TRESORIER'
                        || $role->getLibelleRole() === 'SURVEILLANT')
                        {
                            $data[$i]=$role;
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
                    
                }
                
             return $this->json($data, 200);
            }
            /**
             *@Route("/rolesCompte", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Inscrire::class);
                $pro = $repo->findAll();
               $somme=0;
             
                $ucsercon =$this->getUser();
                //dd($ucsercon);
                
                    foreach($pro as $cl)
                    {
                           
                            $somme++;
                        
                    }

                    //  dd($somme);
                    
                
                
                return $this->json($somme, 201);
            }

}

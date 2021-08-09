<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Professeur;
use App\Generateur\GenererMatProf;
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
class ProfesseurController extends AbstractController
{

    /**
     * @Route("/addProf", name="AjoutProfesseur" ,methods={"POST"})
     */

    public function new(Request $request, SerializerInterface $serializer, GenererMatProf $generer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $professeur = $serializer->deserialize($request->getContent(), Professeur::class, 'json');
        $errors = $validator->validate($professeur);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $users = $this->getUser();
        if ($users->getRoles() == ["ROLE_DIRECTEUR"]) {
            /*permet de recuperer la 1er lettre du prenom ,la 1er lettre du nom et la date de naiss suivi de l'id 
             pour generer le matricule du professeur*/
            $matricule = substr($professeur->getPrenompr() ,0,1) . substr($professeur->getNompr(),0,1)
                .date_format($professeur->getDatenaisspr(),'dmY').$generer->generer();

            $professeur->setMatriculepr(strtoupper($matricule));
            $entityManager->persist($professeur);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Professeur '.$professeur->getPrenompr().' Inscrit(e) '
            ];
            return new JsonResponse($data, 200);
        }

        $data = [
            'status' => 201,
            'message' => 'vous n\'avez pas les autorisation'
        ];
        return new JsonResponse($data, 200);
    }


    //suppression
    /**
    * @Route("/deleteProf/{id}", name="deleteProfesseur", methods={"DELETE"})
    */
    public function delete($id)
    {
        $users = $this->getUser();

        $repons = $this->getDoctrine()->getRepository(Professeur::class);
                $status='';
                $professeur=$repons->find($id);
                $users = $this->getUser();
           if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($professeur);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'professeur '.$professeur->getPrenompr().' Supprimé(e) avec succès !!!'
                    ];
                    return new JsonResponse($data, 200);
                }
                $data = [
                    'status' => 200,
                    'message' => 'Vous n\'avez pas les autorisations'
                    ];
                    return new JsonResponse($data, 200);
    }

    
    //modification prof
    /**
    * @Route("/editeProfesseur/{id}", name="editeProfesseur", methods={"PUT"})
    */
    public function update($id,Request $request,GenererMatProf $generer, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
        {
            $profMod = $entityManager->getRepository(professeur::class)->find($id);
                
            $data =json_decode($request->getContent());
            $errors = $validator->validate($profMod);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                 return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $users = $this->getUser();
                $prof =$serializer->deserialize($request->getContent(), professeur::class, 'json');
            if ($users->getRoles()==["ROLE_DIRECTEUR"]) {
                $profMod->setPrenompr(strtoupper($data->prenompr));
                $profMod->setNompr(strtoupper($data->nompr));
                $profMod->setAdressepr(strtoupper($data->adressepr));
                $profMod->setTelpr(strtoupper($data->telpr));
                $profMod->setDatenaisspr($profMod->getDatenaisspr());
                $profMod->setLieunaisspr(strtoupper($data->lieunaisspr));
                $matricule = substr($profMod->getPrenompr() ,0,1) . substr($profMod->getNompr(),0,1)
                .date_format($profMod->getDatenaisspr(),'dmY').$profMod->getId();
                $profMod->setMatriculepr(strtoupper($matricule));
                $entityManager->persist($profMod);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Professeur Modifié(e) avec succès'
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
     * @Route("/listeProfesseur", name="listeProfesseur" )
     */
    public function liste(){

        $list=$this->getDoctrine()->getRepository(Professeur::class);
        $val=$list->findAll();

        return $this->json($val);
    }

     /**
     * @Route("/gerProfByMat/{matiere}", name="consulterNoteEleve" )
     */
    public function consulterNoteDevEleve($matiere){
        $data=[];
        $i= 0;
        $moye=$this->getDoctrine()->getRepository(Matiere::class);
        $val=$moye->findAll();
        foreach($val as $notes){
            if($matiere==$notes->getId()){
                $data[$i]=$notes;
                  $i++;
            }
            
        }
        return $this->json($data, 200);
    }

      /**
             *@Route("/profs", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Professeur::class);
                $pro = $repo->findAll();
               
                $somme=0;
             
                // $ucsercon =$this->getUser();
                //dd($ucsercon);
                
                    foreach($pro as $cl)
                    {
                           
                            $somme++;
                        
                    }

                    //  dd($somme);
                    
                
                
                return $this->json($somme, 201);
            }
}

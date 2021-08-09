<?php

namespace App\Controller;

use App\Entity\ClasseMatiere;
use App\Entity\EmploisDuTepms;
use App\Entity\Matiere;
use App\Entity\Professeur;
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
class EmploisController extends AbstractController
{

    /**
     * une function on lui donne une classe et il nous retourne les matieres 
     * conserne 
     * @Route("/cherche_mats/{cl}",name="getmat" ,methods={"GET"})
     */
    
    public function getMatByCl($cl)
    {
     
        $eleve = $this->getDoctrine()->getRepository(ClasseMatiere::class);
        $ByMat = $eleve->findAll();
        $data=[];
        $i=0;
         foreach ($ByMat as  $value)
            {
                //   dd($value); die;
            if ($cl == $value->getClasses()->getId()){
               $data[$i]=$value->getMatieres();
               $i++;
              
            }
            }
            return $this->json($data, 200);
     }   


     /**
     * une function on lui donne une matieres et il nous retourne les profs 
     * consernes 
     * @Route("/ProfByMat/{mat}" ,methods={"GET"})
     */
    
    public function getPrsByMat($mat)
    {
     
       $matco =$this->getDoctrine()->getRepository(Matiere::class);
        $matcoef=$matco->findAll();
        //  dd($matcoef);
        $data=new Professeur;
        $i=0;
         foreach ($matcoef as  $value)
            {
                if ($value->getId()==$mat) {
                    $data=$value->getProfesseurs();
                    
                    
            }
            // dd($data);
              
            
                
              
            
            }
            return $this->json($data, 200);
     }   
     


     



    /**
     * ajout un emploie_du_temps
     * @Route("/addEmp" ,methods={"POST"})
     */
    
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $cl_mt =$serializer->deserialize($request->getContent(), EmploisDuTepms::class, 'json');
        $date=new \DateTime('now');//la date
        $val = json_decode($request->getContent());
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
                'message' => 'Cours Cree avec success'
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
     * @Route("/consulterEmp/{classe},{an}", name="consultationEmp" )
     */
    public function consulterNoteSemEleve($classe,$an){
        $data=[];
        $i= 0;
        $emp=$this->getDoctrine()->getRepository(EmploisDuTepms::class);
        $val=$emp->findAll();
        foreach($val as $emps){
            /**
             * verifier si la classe donnée dispose d'un
             * emplois du temps 
             */
            if($emps->getClasses()->getId()==$classe
            && $emps->getAnnees()->getId()==$an){
                $data[$i]=$emps;
                  $i++;
            }
        }
        return $this->json($data, 200);
    }
}

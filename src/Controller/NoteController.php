<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Eleve;
use App\Entity\Appreciation;
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
class NoteController extends AbstractController
{
        /**
         * fonction qui teste si un matricule existe
         */
    public function Matricule($matriculeEleve)
    {
     
        $eleve = $this->getDoctrine()->getRepository(Eleve::class);
        $all = $eleve->findAll();
        //  dd($partenaire); die;
         foreach ($all as  $value)
            {
            if ($matriculeEleve==$value->getMatriculeEleve()){
               
                return true;
            }
            }
        
     }

            
        /**
         * recuperer automatiquement l'aapreciation selon la note donnee
         */
            public function Appreciation($val)
            {
                //  $values=json_decode($request->getContent());
                $frai = $this->getDoctrine()->getRepository(Appreciation::class);
                $all = $frai->findAll();
            //    var_dump($all); die;
                foreach($all as $vals)
                {
                    
                    if($vals->getValInf() <= $val && $vals->getValSup() >= $val)
                    {
                        // dd($vals);
                        return $vals->getLibapp(); 
                    }
                }
            

            }
     
            /**
            * @Route("/ajout_note", methods={"POST"})
            */
    public function new(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
    

          //utilisateur qui connecte
          $users= $this->getUser();
 
          $date=new \DateTime();
 
          $note = $serializer->deserialize($request->getContent(), Note::class, 'json');
         //  $depot = $serializer->deserialize($request->getContent(), Depot::class, 'json');
 
        $val = json_decode($request->getContent());
 
        $errors = $validator->validate($note);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        if ($users->getRoles()==["ROLE_DIRECTEUR"] || $users->getRoles()==["ROLE_SECRETAIRE"]) 
        {
                if( $this->Matricule($val->matriculeEleve)==true)
                {
                    /**
                     * si la fonction retourne true on 
                     * recupere la ligne qui correspond a cet matricule 
                     */
                    $part =$this->getDoctrine()->getRepository(Eleve::class);
                    $ligneEleve=$part->findOneByMatriculeEleve($val->matriculeEleve);
                    
                    //recuperer l'appreciation selon la note
                        $app=$this->Appreciation($note->getValeur());
                    //    dd($app);

                    // $matco =$this->getDoctrine()->getRepository(ClasseMatiere::class);
                    // $matcoef=$matco->find($note->getMats());
                    //  dd($matcoef->getCoef());

                    $note->setEleves($ligneEleve);
                    $note->setApreciation($app);
                    $entityManager->persist($note);
                    $entityManager->flush();


                        
                        $data = [
                            'status' => 201,
                            'message' => 'Note '. $note->getValeur().
                            ' est attribuée à l\'eleve '.$note->getEleves()->getPrenom().' '.
                            $note->getEleves()->getNom().
                            ' Pour La Matiere: '.$note->getMats()->getLibellemat().'('
                            .$note->getTypeNote()->getLibtn().')'.' pour le semestre '
                            .$note->getSems()->getCodesem()
                        ];
                        return new JsonResponse($data, 200);

                }
                /**
                 * sinon on retourne ca
                 */
        
             $data = [
                 'status' => 500,
                 'message' => 'Desolé Cet eleve N\'existe Pas'
             ];
             return new JsonResponse($data, 200);
    } 
    $data = [
                'status' => 500,
                'message' => 'Desolé  Vous n\'avez pas les autorisations necessaires'
            ];
            return new JsonResponse($data, 200);
     }

            /**
             * @Route("/edite_Note/{id}", methods={"PUT"})
             */
            public function update($id,Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
            {
                $userUpdate = $entityManager->getRepository(Note::class)->find($id);
        //   $note = $serializer->deserialize($request->getContent(), Note::class, 'json');
                
                $data =json_decode($request->getContent());
          

                $errors = $validator->validate($userUpdate);
                if(count($errors)) {
                    $errors = $serializer->serialize($errors, 'json');
                    return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }
                $userUpdate->setValeur(($data->valeur));
                $entityManager->persist($userUpdate);
                $entityManager->flush();
                $data = [
                    'status' => 200,
                    'message' => 'Midification  reussie avec succès'
                ];
                return new JsonResponse($data);
            }
            //fonction somme des notes

    public function sommNoteSemDev($mat,$sem,$ann)
    {
        $note=$this->getDoctrine()->getRepository(Note::class);
        $val=$note->findAll();
        
        $somm=0;
        foreach ($val as $notes) {
            
            if ($mat == $notes->getEleves()->getMatriculeEleve() && 
                $sem == $notes->getSems()->getId() &&
                $ann == $notes->getAnnee()->getId()) {
                
                 $somm= $somm + $notes->getValeur() ;
                 
            }   
            
        }
       
        return $somm;   
    }
    //fonction somme des coeffes
    
     public function sommCoeff($mat,$sem,$ann)
     {
         $coeff=$this->getDoctrine()->getRepository(Note::class);
         $val=$coeff->findAll();
         $somm=0;
         foreach ($val as $coeffs) 
         {
               if ($mat == $coeffs->getEleves()->getMatriculeEleve() && 
                 $sem == $coeffs->getSems()->getId() &&
                 $ann == $coeffs->getAnnee()->getId()) 
                 {
                    
                    $matco =$this->getDoctrine()->getRepository(ClasseMatiere::class);
                   $matcoef=$matco->findOneBy(array('matieres' => $coeffs->getMats()));
                //    dd('ddd');
                  
                     $somm=$somm + $matcoef->getCoef() ;
  
                }
   
         }
                 return $somm;  
     }
     
      /**
      * @Route("/CalculeMoyenne/{mat},{sem},{ann}", name="CalculeMoyenne" )
      */
      public function CalculeMoyenne($mat,$sem,$ann)
      {
          $moye=$this->getDoctrine()->getRepository(Note::class);
          $val=$moye->findAll();
          $moyenne=0;
          
          
        //    dd($SCoeff);
    //    dd($SNoteS);
            //    
          foreach ($val as $notes) {
	 //appele fonction somme Des Notes examen
		 $SNoteS = $this->sommNoteSemDev($mat,$sem,$ann);
           //appele fonction somme Des Coeffes

           $SCoeff = $this->sommCoeff($mat,$sem,$ann);
              if ($mat == $notes->getEleves()->getMatriculeEleve() &&
               $sem == $notes->getSems()->getId()&&
               $ann == $notes->getAnnee()->getId()) {
                 $moyenne= ($SNoteS/$SCoeff);
               
               }
            }
             return $this->json(number_format($moyenne,2));
         
      
      }
     //consultation Notes
     /**
      * @Route("/consulterNoteSemEleve/{matricule},{sem},{an}", name="consultationNoteEleve" )
      */
      public function consulterNoteSemEleve($matricule,$sem,$an){
          $data=[];
          $i= 0;
          $moye=$this->getDoctrine()->getRepository(Note::class);
          $val=$moye->findAll();
          foreach($val as $notes){
              if($matricule==$notes->getEleves()->getMatriculeEleve()
              && $sem == $notes->getSems()->getId()
              && $notes->getAnnee()->getId()==$an){
                  $data[$i]=$notes;
                    $i++;
              }
          }
          return $this->json($data, 200);
      }
    /**
     * @Route("/consulterNoteDevEleve/{matricule},{dev}", name="consulterNoteEleve" )
     */
    // public function consulterNoteDevEleve($matricule,$dev){
    //     $data=[];
    //     $i= 0;
    //     $moye=$this->getDoctrine()->getRepository(Note::class);
    //     $val=$moye->findAll();
    //     foreach($val as $notes){
    //         if($matricule==$notes->getEleves()->getMatriculeEleve()&& $dev == $notes->getDevoirs()->getCodedev()){
    //             $data[$i]=$notes;
    //               $i++;
    //         }
            
    //     }
    //     return $this->json($data, 200);
    // }

}

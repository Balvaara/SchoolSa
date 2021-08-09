<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Inscrire;
use App\Entity\Payement;
use App\Generateur\GenererNumIns;
use App\Generateur\GenererNumPayement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/api")
 */
class PayementController extends AbstractController
{

    

    public function MatriculeEleve($mat,$ann)
    {
     
        $eleve = $this->getDoctrine()->getRepository(Inscrire::class);
        $ByMat = $eleve->findAll();
        //  dd($partenaire); die;
         foreach ($ByMat as  $value) 
            {
            if ($mat == $value->getEleves()->getMatriculeEleve() &&
                $value->getSession()->getId()==$ann){
               
                return true;
            }
            }
        
     }
    
         
     /**
     * @Route("/ajout_payement", methods={"POST"})
     */
    public function Payement(Request $request,GenererNumPayement $generer, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $users = $this->getUser();

        $Payement = $serializer->deserialize($request->getContent(), Payement::class, 'json');

        $val = json_decode($request->getContent());
        
        $date=new \DateTime('now');//la date du payement
        

        $errors = $validator->validate($Payement);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        if ($users->getRoles()==["ROLE_DIRECTEUR"] || $users->getRoles()==["ROLE_SECRETAIRE"]
        || $users->getRoles()==["ROLE_TRESORIER"]) 
        {

       
     
        if ($this->MatriculeEleve($val->matriculeEleve,$val->session)==true) 
        {
            // dd('jjj');
            //tester si la fonction retourne vrai: esk le matricule de cet eleve existe

            $part =$this->getDoctrine()->getRepository(Eleve::class);
            $lignePart=$part->findOneBy(array('matriculeEleve'=>$val->matriculeEleve));
            
            $ins =$this->getDoctrine()->getRepository(Inscrire::class);
            $ligneins=$ins->findOneBy(array('eleves' => $lignePart,'session'=>$val->session));
            
        //  dd($ligneins->getClasses()->getMontantMens());
            //on recupere la ligne qui correspond a cet marticule

           $gen=$lignePart->getMatriculeEleve().date_format($date,'Ymd').$generer->generer();
        //    dd($gen);
            /*  numero du payement genere c'est le matricule de l'eleve + date d payement (A/M/J)
            et le dernier Id de la table payement */

        //    $Payement->setMontant(0);
           $Payement->setInscrire($ligneins);
           $Payement->setDateDePayement($date);
           $Payement->setMontantMensualite($ligneins->getClasses()->getMontantMens());
           $Payement->setNumPaye($gen);
        //    $Payement->setMois($gen);
           $entityManager->persist($Payement);
           $entityManager->flush();

           $data = [
            'status' => 200,
            'message' => 'Payement effectué pour le mois de '.
            $Payement->getMois()->getLibellemois().' de l\'eleve: '.
            $Payement->getInscrire()->getEleves()->getPrenom().' '.
            $Payement->getInscrire()->getEleves()->getNom().' Annee Scolaire: '.
            $ligneins->getSession()->getLibAnn()  
        ];
        return new JsonResponse($data, 200);
    
 
     }
        /* si le matricle n'exixte pas
        */
        $data = [
                    'status' => 500,
                    'message' => 'Desolé  cet eleve n\'a pas encore inscrit cette annee'
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
             *@Route("/get_budjet/{mois}", methods={"GET"})
            */
            public function Budjet($mois)
            {
                /**
                 * la somme des payements d'un mois
                 */
                $repo = $this->getDoctrine()->getRepository(Payement::class);
                $paye = $repo->findAll();
               
                
                $somme =0;
                // $ucsercon =$this->getUser();
                //dd($ucsercon);
                
                    foreach($paye as $payement)
                    {
                        if ($payement->getMois()->getId()==null ||
                        $payement->getMois()->getId()==$mois) {
                            // dd('djdj');
                            $somme =$somme+$payement->getMontant();
                        }
                        
                    }
                    $data = [
                        'status' => 200,
                        'message' => 'La Somme Des Payements effectués pour le mois de '.
                        $mois.' est '.$somme
                    ];
                    return new JsonResponse($data, 200);
            }


             /**
             *@Route("/get_pay/{mois},{ses}", methods={"GET"})
            */
            public function getPay($mois,$ses)
            {
                $data=[];
                $i= 0;
                $tab=[];
                $j= 0;
                $repo = $this->getDoctrine()->getRepository(Payement::class);
                $paye = $repo->findAll();
    
                    foreach($paye as $payement)
                    { 
                        if ($payement->getMois()!==null 
                        && $payement->getInscrire()->getSession()->getId()==$ses) {
                        
                            $data[$i]=$payement;
                            $i++;
                        }
                    }
                    // dd($data);
                    foreach ($data as $value) {
                       if ($value->getMois()->getId()==$mois) {
                            $tab[$j]=$value;
                            $j++;
                       }
                    }
                   
                    return $this->json($tab, 200);
            }


             /**
             *@Route("/get_pay_by_mat/{matriculeEleve},{ses}", methods={"GET"})
            */
            public function getPayByMat($matriculeEleve,$ses)
            {
                $data=[];
                $i= 0;
                $repo = $this->getDoctrine()->getRepository(Payement::class);
                $paye = $repo->findAll();
    
                    foreach($paye as $payement)
                    {
                        /**
                         * tester si le matricule donne a effectuer un payement dans une annee
                         */
                        if ($payement->getInscrire()->getEleves()->getMatriculeEleve()==$matriculeEleve
                        && $payement->getMois()!==null
                        && $payement->getInscrire()->getSession()->getId()==$ses) {

                        /**
                         * on recupere l'ensemble de ses payement dans cette annee
                         */
                            $data[$i]=$payement;
                            $i++;
                        }
                    }
                   
                    return $this->json($data, 200);
            }
        
            public function getbudByIns()
            {
                    $somme=0;
                    $repo = $this->getDoctrine()->getRepository(Inscrire::class);
                    $paye = $repo->findAll();
        
                    foreach($paye as $payement)
                    {
                        $somme=$somme+$payement->getMontant();
                    }  
                    return $somme;
            }

             /**
            *@Route("/somme", methods={"GET"})
            */
            public function getbud()
            {
                    $somme1=0;
                    $somme2=0;
                    // $budins=$this->getbudByIns();
                    $repo = $this->getDoctrine()->getRepository(Payement::class);
                    $paye = $repo->findAll();
        
                    foreach($paye as $payement)
                    {
                        $somme1=$somme1+$payement->getMontant()+$payement->getMontantMensualite();
                    } 
                     
                    // $somme2=$somme1+$budins;
                    return $this->json($somme1, 200);
            }

              /**
            *@Route("/getClasse/{ses},{mat}", methods={"GET"})
            */
            public function getClasse($ses,$mat)
            {
                $part =$this->getDoctrine()->getRepository(Eleve::class);
                $lignePart=$part->findOneBy(array('matriculeEleve'=>$mat));

                $ins =$this->getDoctrine()->getRepository(Inscrire::class);
                $ligneins=$ins->findOneBy(array('eleves' => $lignePart,'session'=>$ses));
                     
                    // $somme2=$somme1+$budins;
                    return $this->json($ligneins, 200);
            }
}



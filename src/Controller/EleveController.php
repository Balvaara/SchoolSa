<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Classe;
use App\Entity\Parrent;
use App\Entity\Inscrire;
use App\Entity\Payement;
use App\Entity\AnneeAcad;
use App\Entity\Mois;
use App\Generateur\GenererNumIns;
use App\Generateur\GenererNumPayement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
* @Route( "/api" )
*/

class EleveController extends AbstractController {


    public function MatriculeEleve($mat)
    {
     
        $eleve = $this->getDoctrine()->getRepository(Eleve::class);
        $ByMat = $eleve->findAll();
        //  dd($partenaire); die;
         foreach ($ByMat as  $value)
            {
            if ($mat == $value->getMatriculeEleve()){
               
                return true;
            }
            }
        
     }
    
    /**
     * ajouter un niuveau eleve
    * @Route( "/ajouteleve", name = "inscrire_eleve", methods = {"POST"})
    */

    public function inscrire_eleve_niv( Request $request,GenererNumIns $generer,GenererNumPayement $generer1, EntityManagerInterface $entityManager, ValidatorInterface $validator ) 
    {

        $champs = json_decode($request->getContent());
        //var_dump( $champs ); die;
        $today =$dateCreate=new \DateTime();
        $data =json_decode($request->getContent());
    //    $mySession=($anne.'-'.$anne1);
       
             //generer matricule de l'eleve.
        $deleve = $this->getLasteleve();
        $plp=$champs->prenom[0];
        $pln=$champs->nom[0];
        $mydate= \DateTime::createFromFormat('Y-m-d', $champs->datenaissance);
        // dd();	
        // $matIns=strtoupper($plp.$pln.date_format($mydate,'dmY'));
        $matricule=strtoupper($plp.$pln.date_format($mydate,'dmY').$deleve);

        //gener num insciption mat+date+id
        $numIns=$matricule.date_format($today,'dmY').$generer->generer();

        //gener num payement mat+date+id
        $gennumpay=$matricule.date_format($today,'Ymd').$generer1->generer();
        //  dd($gennumpay);
            $eleve = new Eleve();
            $parrent = new Parrent();
            $inscrire = new Inscrire();
            $payement = new Payement();
             // affectation des class
             //creations d'une nouvelle instance parrent.
             $ripo = $this->getDoctrine()->getRepository(Parrent::class);
             $tuteur = $ripo->findOneBy(array('telp' => $champs->telephone));
             
            if($tuteur == NULL)
                {	
                    $parrent->setPrenomp($champs->prenomparrent);
                    $parrent->setNomp($champs->nomp);
                    $parrent->setAdressep($champs->adresse);
                    $parrent->setTelp($champs->telephone);

                    $entityManager->persist($parrent);
                    $entityManager->flush(); 

                    $tuteur=$parrent;
                }
                $niveleve = $this->getDoctrine()->getRepository(Classe::class);
                $classes = $niveleve->find($champs->classe);
                //creations d'une nouvelle instance Eleve.
                $celeve=$classes;

                $ligneAnnee = $this->getDoctrine()->getRepository(AnneeAcad::class);
                $an = $ligneAnnee->find($champs->session);
                //creations d'une nouvelle instance annee.
                $myannee=$an;

                // $lignMois = $this->getDoctrine()->getRepository(Mois::class);
                // $mois = $lignMois->find($champs->mois);
                // //creations d'une nouvelle instance mois.
                // $moispaye=$mois;

                $eleve->setPrenom($champs->prenom);
                $eleve->setNom($champs->nom);
                $eleve->setDatenais(\DateTime::createFromFormat('Y-m-d', $champs->datenaissance));
                $eleve->setLieunaiss($champs->lieu);
                $eleve->setSexe($champs->sexe);
                // $eleve->setClasses($celeve);
                $eleve->setParrents($tuteur);
                $eleve->setMatriculeEleve($matricule);

                $entityManager->persist($eleve);
                $entityManager->flush(); 

                //creations d'une nouvelle instance Inscrire.
                $inscrire->setEleves($eleve);
                $inscrire->setClasses($celeve);
                $inscrire->setSession($myannee);
                $inscrire->setDateIns($today);
                $inscrire->setNumIns($numIns);
                $entityManager->persist($inscrire);
                $entityManager->flush();

                //creations d'une nouvelle instance payement.

                $payement->setMontant($inscrire->getClasses()->getMontantIns());
                $payement->setNumPaye($gennumpay);
                $payement->setDateDePayement($today);
                $payement->setInscrire($inscrire);
                // $payement->setMois($moispaye);
                // $payement->setMontantMensualite($inscrire->getClasses()->getMontantMens());
                $entityManager->persist($payement);
                $entityManager->flush();
                $data = [
                    'status' => 201,
                    'message' => 'Nouvel Eleve inscrit avec success.'
                ];
        
                return new JsonResponse($data, 201);
               
       
    }
            public function getLasteleve()
            {
                $ripo = $this->getDoctrine()->getRepository(Eleve::class);
                $compte = $ripo->findBy([], ['id'=>'DESC']);
                if(!$compte)
                {
                    $cont= 1;
                }else
                {
                    $cont = ($compte[0]->getId()+1);
                }
                return $cont;
            }


                /**
                 * ajouter un envien eleve
                * @Route( "/ajouteleve_encien", methods = {"POST"})
                */

    public function inscrire_eleve_encien( Request $request,GenererNumIns $generer,GenererNumPayement $generer1, EntityManagerInterface $entityManager, ValidatorInterface $validator ) 
    {

        $champs = json_decode($request->getContent());
      
        $today =$dateCreate=new \DateTime();
        // $data =json_decode($request->getContent());
  
       
        // $matIns=strtoupper($plp.$pln.date_format($mydate,'dmY'));
        // $matricule=strtoupper($plp.$pln.date_format($mydate,'dmY').$deleve);

       
        if ($this->MatriculeEleve($champs->matriculeEleve)==true) 
        {
            //tester si la fonction retourne vrai: esk le matricule de cet eleve existe

            $part =$this->getDoctrine()->getRepository(Eleve::class);
            $lignePart=$part->findOneByMatriculeEleve($champs->matriculeEleve);
            // dd($lignePart);
            // gener num insciption mat+date+id
            $numIns=$lignePart->getMatriculeEleve().date_format($today,'dmY').$generer->generer();

            //gener num payement mat+date+id
            $gennumpay=$lignePart->getMatriculeEleve().date_format($today,'Ymd').$generer1->generer();

            //  dd($gennumpay);
            $inscrire = new Inscrire();
            $payement = new Payement();
            
            
                
                $niveleve = $this->getDoctrine()->getRepository(Classe::class);
                $classes = $niveleve->find($champs->classe);
               

                $ligneAnnee = $this->getDoctrine()->getRepository(AnneeAcad::class);
                $an = $ligneAnnee->find($champs->session);
                //creations d'une nouvelle instance annee.
               

                // $lignMois = $this->getDoctrine()->getRepository(Mois::class);
                // $mois = $lignMois->find($champs->mois);
                //creations d'une nouvelle instance mois.
               

              

                //creations d'une nouvelle instance Inscrire.
                $inscrire->setEleves($lignePart);
                $inscrire->setClasses($classes);
                $inscrire->setSession($an);
                $inscrire->setDateIns($today);
                 $inscrire->setNumIns($numIns);
                $entityManager->persist($inscrire);
                $entityManager->flush();

                //creations d'une nouvelle instance payement.

                $payement->setMontant($inscrire->getClasses()->getMontantIns());
                $payement->setNumPaye($gennumpay);
                $payement->setDateDePayement($today);
                $payement->setInscrire($inscrire);
                // $payement->setMois($mois);
                // $payement->setMontantMensualite($inscrire->getClasses()->getMontantMens());
                $entityManager->persist($payement);
                $entityManager->flush();

       
                $data = [
                    'status' => 201,
                    'message' => 'Eleve réinscrit a nouveau.'
                ];
        
                return new JsonResponse($data, 201);
               
            }
            $data = [
                'status' => 201,
                'message' => 'Eleve n\'existe pas .'
            ];
    
            return new JsonResponse($data, 201);
        
       
    }


    /**                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
     * @Route("/updateeleve/{id}", name="modif_eleve", methods={"PUT"})
     */

    public function miseajour_eleve($id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator ) 
    {
        $contenu =json_decode($request->getContent());
        
        
        if(isset($contenu->nom,$contenu->prenom,$contenu->datenaissance,
        $contenu->lieu,$contenu->sexe))
        {
                //regenerer matricule de l'eleve.
        $deleve = $this->getLasteleve();
        $plp=$contenu->prenom[0];
        $pln=$contenu->nom[0];
        $mydate= \DateTime::createFromFormat('Y-m-d', $contenu->datenaissance);
        // dd();
        $matricule=strtoupper($plp.$pln.date_format($mydate,'dmY').$id);

            $eleves = $this->getDoctrine()->getRepository(Eleve::class);
            $eleve = $eleves->find($id);
            // $niveleve = $this->getDoctrine()->getRepository(Classe::class);
            // $classes = $niveleve->find($contenu->classe);
            // $ripos =$this->getDoctrine()->getRepository(Inscrire::class);
            // $les_inscriptions =  $ripos->findAll();

          
            
            // foreach($les_inscriptions as $indice)
            //     {
            //         $la_ligne =$indice->getEleves()->getId();
            //         $compar=$la_ligne;
                   
            //         if($compar == $id)
            //         {
            //             //var_dump( $compar); die();
            //             $trouve=$indice;
            //         }
                    
            //     }
                
                // $resultats=$trouve;
                // dd($resultats->getNumIns());
            if($eleve)
            {
            // la ligne qu'on veux modifiee
            $ele = $eleve;
           
            //dd($ele);

            //on modifie l'eleve dont Id a ete passe
                $ele->setPrenom($contenu->prenom);
                $ele->setNom($contenu->nom);
                $ele->setDatenais(\DateTime::createFromFormat('Y-m-d', $contenu->datenaissance));
                $ele->setLieunaiss($contenu->lieu);
                $ele->setSexe($contenu->sexe);
                $ele->setMatriculeEleve($matricule);
                $entityManager->persist($ele);

                //on modifie son  classe dans la table de l'inscription
                // $resultats->setClasses($classes);
                // $entityManager->persist($resultats);

                //on recupere l'ensemble des payements de l'inscription 
                // $pay = $this->getDoctrine()->getRepository(Payement::class);
                // $modpay = $pay->find($resultats->getId());

                 //on modifie les modalite de l'inscription et de mensualite 
                 // selon la classe choissie 
                // $modpay->setMontant($resultats->getClasses()->getMontantIns());
                // $modpay->setMontantMensualite($resultats->getClasses()->getMontantMens());
                // $entityManager->persist($modpay);

                //ajouter dans la base de donne.
               
                $entityManager->flush(); 
                $data = [
                    'status' => 201,
                    'message' => 'Modification reussie avec success .'
                ];
                return new JsonResponse($data, 201); 
            } 
           
    
           
        }
        $data = [
            'status' => 500,
            'message' => "echec du modification."
        ];
         return new JsonResponse($data, 500);
    }
    /** 
     * @Route("/supprimeeleve/{id}", name="mise_eleve", methods={"DELETE"})
     */
    
    public function delete_eleve($id,EntityManagerInterface $entityManager) 
    {
        //recuperation de l'eleve a supprimer.
        $element = $this->getDoctrine()->getRepository(Eleve::class);
        $eleve = $element->find($id);
        $ins=$eleve->getId();

        //recuperation du tuteur et voir s'il doit etre supprimer ou pas.
        $elemts = $element->findAll();
        $parants=$eleve->getParrents()->getId();
        $rip = $this->getDoctrine()->getRepository(Parrent::class);
        $le_parrent = $rip->findOneBy(array('id' => $parants));
        //dd( $le_parrent );
        $cpt=0;
        //$le_tuteur=[];
       
            //recuperation du inscription qui concerne l'eleve.
        $ripo = $this->getDoctrine()->getRepository(Inscrire::class);
        $inscrire = $ripo->findAll();
            foreach($inscrire as $ligne)
                {
                    $student=$ligne->getEleves();
                    $teste=$student->getId();
                    if($teste == $ins)
                    {
                        $t=$teste;
                        $trv=$ligne;
                    }
                    
                }

                //dd($le_parrent);
                 
        if($eleve)
        {
            foreach($elemts as $dad)
            {
                $prt=$dad->getParrents()->getId();
                if($prt == $parants)
                {
                    $cpt=$cpt+1;
                }
            }
            if($cpt==1)
            {
                $entityManager->remove($le_parrent); 
            }
            $entityManager->remove($eleve);
            $entityManager->remove($trv);
            $entityManager->flush(); 

            $data = [
                'status' => 200,
                'message' => 'eleve '.$eleve->getPrenom().' est Supprimé(e) avec succès !!!'
                ];
                return new JsonResponse($data, 200);
        }
        $data = [
            'status' => 500,
            'message' => "echec du suppression."
        ];
         return new JsonResponse($data, 500);
    }

            /**
             *@Route("/nbEleve", methods={"GET"})
            */
            public function count()
            {
                
                $repo = $this->getDoctrine()->getRepository(Eleve::class);
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

	/**
     * @Route("/listIns" )
     */
    public function listeIns(){
        $list=$this->getDoctrine()->getRepository(Inscrire::class);
        $val=$list->findAll();
        
        return $this->json($val);
    }
	/**
     * @Route("/listeEleve",name="myliste",methods={"GET"} )
     */
    public function liste(){
        $list=$this->getDoctrine()->getRepository(Eleve::class);
        $val=$list->findAll();
        
        return $this->json($val);
    }

	/**
     * @Route("/eleves/{id}",methods={"GET"} )
     */
    public function getidel($id){
        $list=$this->getDoctrine()->getRepository(Eleve::class);
        $val=$list->find($id);
        
        return $this->json($val);
    }	
	/**
     * @Route("/inscrires/{id}",methods={"GET"} )
     */
    public function getIdIns($id){
        $list=$this->getDoctrine()->getRepository(Inscrire::class);
        $val=$list->find($id);
        
        return $this->json($val);
    }	

	/**
     * @Route("/getIns/{numIns}" )
     */
    public function listeInsByNum($numIns){
        
        $list=$this->getDoctrine()->getRepository(Inscrire::class);
         $tuteur = $list->findOneBy(array('numIns' => $numIns));
        return $this->json($tuteur);
    }

      /**
     * @Route("/eleveseMat/{matriculeEleve}" )
     */
    public function listElsByMat($matriculeEleve){
        $list=$this->getDoctrine()->getRepository(Eleve::class);
         $tuteur = $list->findOneBy(array('matriculeEleve' => $matriculeEleve));
        
        return $this->json($tuteur);
    }

     /** 
     * @Route("/updatins/{id}", name="modif_ins", methods={"PUT"})
     */

    public function miseajour_ins($id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator ) 
    {
        $contenu =json_decode($request->getContent());
        
        
        if(isset($contenu->classe))
        {
            $ins = $this->getDoctrine()->getRepository(Inscrire::class);
            $inscrire = $ins->find($id);
            $niveleve = $this->getDoctrine()->getRepository(Classe::class);
            $classes = $niveleve->find($contenu->classe);
            
            if($inscrire)
            {
            // la ligne qu'on veux modifiee
            $ele = $inscrire;
           
            //dd($ele);

            //on modifie l'eleve dont Id a ete passe
                $ele->setClasses($classes);
               
                $entityManager->persist($ele);


                //on recupere l'ensemble des payements de l'inscription 
                $pay = $this->getDoctrine()->getRepository(Payement::class);
                $modpay = $pay->find($ele->getId());

                 //on modifie les modalite de l'inscription et de mensualite 
                 // selon la classe choissie 
                $modpay->setMontant($ele->getClasses()->getMontantIns());
                $entityManager->persist($modpay);

                //ajouter dans la base de donne.
               
                $entityManager->flush(); 
                $data = [
                    'status' => 201,
                    'message' => 'Modification reussie avec success .'
                ];
                return new JsonResponse($data, 201); 
            } 
           
    
           
        }
        $data = [
            'status' => 500,
            'message' => "echec du modification."
        ];
         return new JsonResponse($data, 500);
    }


    /**
     * @Route("/getElIns/{mat},{session}" )
     */
    public function getElIns($mat,$session){
        
            $part =$this->getDoctrine()->getRepository(Eleve::class);
            $lignePart=$part->findOneBy(array('matriculeEleve'=>$mat));
            
            $ins =$this->getDoctrine()->getRepository(Inscrire::class);
            $ligneins=$ins->findOneBy(array('eleves' => $lignePart,'session'=>$session));
            
        return $this->json($ligneins);
    }
}

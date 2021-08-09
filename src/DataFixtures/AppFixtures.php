<?php

namespace App\DataFixtures;


use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Runner\Filter\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        // $faker = Faker\Factory::create('fr_FR');

        $supad=new Role();
        $supad->setLibelleRole("DIRECTEUR");
        $manager->persist($supad);

        $ad=new Role();
        $ad->setLibelleRole("SECRETAIRE");
        $manager->persist($ad);

        $cais=new Role();
        $cais->setLibelleRole("TRESORIER");
        $manager->persist($cais);

        $par=new Role();
        $par->setLibelleRole("SURVEILLANT");
        $manager->persist($par);

     


        $this->addReference('role_directeur',$supad);
        $this->addReference('role_secretaire',$ad);
        $this->addReference('role_tresorrier',$cais);
        $this->addReference('role_surveillant',$par);
      

        $roleSupAd=$this->getReference('role_directeur');
        $roleAd=$this->getReference('role_secretaire');
        $roleCais=$this->getReference('role_tresorrier');
        $rolePar=$this->getReference('role_surveillant');
    



        $user = new User();
        $user->setNomComplet('Sonatel Academy');
        $user->setUsername('sonatelacademy');
        $user->setPassword($this->encoder->encodePassword($user, "sonatelacademy"));
        $user->setRoles(["ROLE_".$supad->getLibelleRole()]);
        $user->setProfil($supad);
        // $user->setPartenaire(null);

        $user->setIsActive(true);


        $manager->persist($user);
        $manager->flush();
     

    }
}

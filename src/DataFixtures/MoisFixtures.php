<?php

namespace App\DataFixtures;

use App\Entity\Mois;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MoisFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       

        $paye=new Mois();
        $paye->setLibellemois("JANVIER");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("FEVRIER");
        $manager->persist($paye);
        
        $paye=new Mois();
        $paye->setLibellemois("MARS");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("AVRIL");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("MAI");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("JUIN");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("JUILLET");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("AOUT");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("SEPTEMBRE");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("OCTOBRE");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("NOVEMBRE");
        $manager->persist($paye);
        $paye=new Mois();
        $paye->setLibellemois("DECEMBRE");
        $manager->persist($paye);
        $manager->flush();
    }
}

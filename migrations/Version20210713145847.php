<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713145847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee_acad (id INT AUTO_INCREMENT NOT NULL, lib_ann VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appreciation (id INT AUTO_INCREMENT NOT NULL, libapp VARCHAR(255) NOT NULL, val_inf INT NOT NULL, val_sup INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, niveaux_id INT NOT NULL, series_id INT DEFAULT NULL, codeclasse VARCHAR(255) NOT NULL, libelleclasse VARCHAR(255) NOT NULL, montant_ins INT NOT NULL, montant_mens INT NOT NULL, INDEX IDX_8F87BF96AAC4B70E (niveaux_id), INDEX IDX_8F87BF965278319C (series_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe_matiere (id INT AUTO_INCREMENT NOT NULL, classes_id INT NOT NULL, matieres_id INT NOT NULL, coef INT NOT NULL, INDEX IDX_EB8D372B9E225B24 (classes_id), INDEX IDX_EB8D372B82350831 (matieres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, parrents_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, datenais DATE NOT NULL, lieunaiss VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, matricule_eleve VARCHAR(255) NOT NULL, INDEX IDX_ECA105F79C08A9D8 (parrents_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplois_du_tepms (id INT AUTO_INCREMENT NOT NULL, professeurs_id INT NOT NULL, classes_id INT NOT NULL, mats_id INT NOT NULL, annees_id INT NOT NULL, heur_debut TIME NOT NULL, heur_fin TIME NOT NULL, jours VARCHAR(255) NOT NULL, INDEX IDX_F452E0DE3E1D55D7 (professeurs_id), INDEX IDX_F452E0DE9E225B24 (classes_id), INDEX IDX_F452E0DE587D7BFB (mats_id), INDEX IDX_F452E0DE5A9871FC (annees_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscrire (id INT AUTO_INCREMENT NOT NULL, eleves_id INT NOT NULL, session_id INT NOT NULL, classes_id INT NOT NULL, date_ins DATE NOT NULL, num_ins VARCHAR(255) NOT NULL, INDEX IDX_84CA37A8C2140342 (eleves_id), INDEX IDX_84CA37A8613FECDF (session_id), INDEX IDX_84CA37A89E225B24 (classes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, libellemat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mois (id INT AUTO_INCREMENT NOT NULL, libellemois VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, libelleniveau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, eleves_id INT NOT NULL, sems_id INT NOT NULL, mats_id INT NOT NULL, type_note_id INT NOT NULL, annee_id INT NOT NULL, apreciation VARCHAR(255) NOT NULL, valeur INT NOT NULL, INDEX IDX_CFBDFA14C2140342 (eleves_id), INDEX IDX_CFBDFA1499FD2B8D (sems_id), INDEX IDX_CFBDFA14587D7BFB (mats_id), INDEX IDX_CFBDFA14ECC67A0 (type_note_id), INDEX IDX_CFBDFA14543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parrent (id INT AUTO_INCREMENT NOT NULL, nomp VARCHAR(255) NOT NULL, prenomp VARCHAR(255) NOT NULL, adressep VARCHAR(255) NOT NULL, telp VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payement (id INT AUTO_INCREMENT NOT NULL, mois_id INT DEFAULT NULL, inscrire_id INT DEFAULT NULL, montant INT NOT NULL, num_paye VARCHAR(255) NOT NULL, date_de_payement DATETIME NOT NULL, montant_mensualite INT DEFAULT NULL, INDEX IDX_B20A7885FA0749B8 (mois_id), INDEX IDX_B20A78855A9C42F6 (inscrire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeur (id INT AUTO_INCREMENT NOT NULL, prenompr VARCHAR(255) NOT NULL, nompr VARCHAR(255) NOT NULL, adressepr VARCHAR(255) NOT NULL, telpr VARCHAR(255) NOT NULL, datenaisspr DATE NOT NULL, lieunaisspr VARCHAR(255) NOT NULL, matriculepr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeur_matiere (professeur_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_FBC82ABCBAB22EE9 (professeur_id), INDEX IDX_FBC82ABCF46CD258 (matiere_id), PRIMARY KEY(professeur_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle_role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semestre (id INT AUTO_INCREMENT NOT NULL, codesem VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, codeserie VARCHAR(255) NOT NULL, libelleserie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_note (id INT AUTO_INCREMENT NOT NULL, libtn VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom_complet VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96AAC4B70E FOREIGN KEY (niveaux_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF965278319C FOREIGN KEY (series_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE classe_matiere ADD CONSTRAINT FK_EB8D372B9E225B24 FOREIGN KEY (classes_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE classe_matiere ADD CONSTRAINT FK_EB8D372B82350831 FOREIGN KEY (matieres_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F79C08A9D8 FOREIGN KEY (parrents_id) REFERENCES parrent (id)');
        $this->addSql('ALTER TABLE emplois_du_tepms ADD CONSTRAINT FK_F452E0DE3E1D55D7 FOREIGN KEY (professeurs_id) REFERENCES professeur (id)');
        $this->addSql('ALTER TABLE emplois_du_tepms ADD CONSTRAINT FK_F452E0DE9E225B24 FOREIGN KEY (classes_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE emplois_du_tepms ADD CONSTRAINT FK_F452E0DE587D7BFB FOREIGN KEY (mats_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE emplois_du_tepms ADD CONSTRAINT FK_F452E0DE5A9871FC FOREIGN KEY (annees_id) REFERENCES annee_acad (id)');
        $this->addSql('ALTER TABLE inscrire ADD CONSTRAINT FK_84CA37A8C2140342 FOREIGN KEY (eleves_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE inscrire ADD CONSTRAINT FK_84CA37A8613FECDF FOREIGN KEY (session_id) REFERENCES annee_acad (id)');
        $this->addSql('ALTER TABLE inscrire ADD CONSTRAINT FK_84CA37A89E225B24 FOREIGN KEY (classes_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14C2140342 FOREIGN KEY (eleves_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1499FD2B8D FOREIGN KEY (sems_id) REFERENCES semestre (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14587D7BFB FOREIGN KEY (mats_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14ECC67A0 FOREIGN KEY (type_note_id) REFERENCES type_note (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14543EC5F0 FOREIGN KEY (annee_id) REFERENCES annee_acad (id)');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A7885FA0749B8 FOREIGN KEY (mois_id) REFERENCES mois (id)');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A78855A9C42F6 FOREIGN KEY (inscrire_id) REFERENCES inscrire (id)');
        $this->addSql('ALTER TABLE professeur_matiere ADD CONSTRAINT FK_FBC82ABCBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professeur_matiere ADD CONSTRAINT FK_FBC82ABCF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplois_du_tepms DROP FOREIGN KEY FK_F452E0DE5A9871FC');
        $this->addSql('ALTER TABLE inscrire DROP FOREIGN KEY FK_84CA37A8613FECDF');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14543EC5F0');
        $this->addSql('ALTER TABLE classe_matiere DROP FOREIGN KEY FK_EB8D372B9E225B24');
        $this->addSql('ALTER TABLE emplois_du_tepms DROP FOREIGN KEY FK_F452E0DE9E225B24');
        $this->addSql('ALTER TABLE inscrire DROP FOREIGN KEY FK_84CA37A89E225B24');
        $this->addSql('ALTER TABLE inscrire DROP FOREIGN KEY FK_84CA37A8C2140342');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14C2140342');
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A78855A9C42F6');
        $this->addSql('ALTER TABLE classe_matiere DROP FOREIGN KEY FK_EB8D372B82350831');
        $this->addSql('ALTER TABLE emplois_du_tepms DROP FOREIGN KEY FK_F452E0DE587D7BFB');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14587D7BFB');
        $this->addSql('ALTER TABLE professeur_matiere DROP FOREIGN KEY FK_FBC82ABCF46CD258');
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A7885FA0749B8');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96AAC4B70E');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F79C08A9D8');
        $this->addSql('ALTER TABLE emplois_du_tepms DROP FOREIGN KEY FK_F452E0DE3E1D55D7');
        $this->addSql('ALTER TABLE professeur_matiere DROP FOREIGN KEY FK_FBC82ABCBAB22EE9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1499FD2B8D');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF965278319C');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14ECC67A0');
        $this->addSql('DROP TABLE annee_acad');
        $this->addSql('DROP TABLE appreciation');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE classe_matiere');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE emplois_du_tepms');
        $this->addSql('DROP TABLE inscrire');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE mois');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE parrent');
        $this->addSql('DROP TABLE payement');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP TABLE professeur_matiere');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE semestre');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE type_note');
        $this->addSql('DROP TABLE user');
    }
}

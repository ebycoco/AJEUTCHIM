<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310131221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Ajeutchim_adhesions (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_AF6C5EC9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_ajeutchim (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, contenue LONGTEXT NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_B57BAEF5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_annuellecotisations (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_5FBED0816A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_apropos (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, contenue LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_BBEA1881A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_articles (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, categorie_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, published_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_13FE62CEA76ED395 (user_id), INDEX IDX_13FE62CEBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_bilan (id INT AUTO_INCREMENT NOT NULL, depense DOUBLE PRECISION DEFAULT NULL, adhesion DOUBLE PRECISION DEFAULT NULL, cotisation DOUBLE PRECISION DEFAULT NULL, versement DOUBLE PRECISION DEFAULT NULL, annee VARCHAR(4) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_bureaux (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, post_ajeutchim_id INT NOT NULL, president_id INT NOT NULL, etat INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_8F6999DF6A99F74A (membre_id), INDEX IDX_8F6999DFEE137E2C (post_ajeutchim_id), INDEX IDX_8F6999DFB40A33C7 (president_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_candidats (id INT AUTO_INCREMENT NOT NULL, candidature_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nombre_voix INT DEFAULT NULL, etat TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_37F1E9BB6121583 (candidature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_candidatures (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, matricule_ajeutchim VARCHAR(255) NOT NULL, droit INT NOT NULL, image_candidat VARCHAR(255) NOT NULL, image_programme VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_categories (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_30CCF16AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_comments (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, content LONGTEXT NOT NULL, posted_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_F3BDC58C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_contacts (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero_tel INT NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_cotisations (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, annee DATE NOT NULL, reste_montant DOUBLE PRECISION DEFAULT \'0\' NOT NULL, montant_total_paye DOUBLE PRECISION NOT NULL, status INT DEFAULT 0 NOT NULL, neplus TINYINT(1) DEFAULT \'0\' NOT NULL, an VARCHAR(4) NOT NULL, montantannuelle DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_2B1F6B876A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_decaissements (id INT AUTO_INCREMENT NOT NULL, depense_id INT NOT NULL, user_id INT NOT NULL, bureau_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, frais DOUBLE PRECISION NOT NULL, jour DATE NOT NULL, annee VARCHAR(4) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_6250884341D81563 (depense_id), INDEX IDX_62508843A76ED395 (user_id), INDEX IDX_6250884332516FE2 (bureau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_depenses (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, confirme TINYINT(1) DEFAULT \'0\' NOT NULL, etat INT DEFAULT 0 NOT NULL, montant DOUBLE PRECISION NOT NULL, montanpaye DOUBLE PRECISION DEFAULT NULL, annee VARCHAR(4) NOT NULL, rejeter TINYINT(1) DEFAULT NULL, visible TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_42165D6DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_desactives (id INT AUTO_INCREMENT NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_dons (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, numero_tel INT NOT NULL, montant INT NOT NULL, email VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, id_reference INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_evenementRealiser (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, evented_at DATETIME DEFAULT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_290CE427A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_flashs (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_97AADEA8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_imageAccueils (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_319DDD1DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_mandats (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, post_ajeutchim_id INT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_72035C946A99F74A (membre_id), INDEX IDX_72035C94EE137E2C (post_ajeutchim_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_membreconseils (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, post VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_95F243476A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_membres (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, contact VARCHAR(255) NOT NULL, profession VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, reference_ajeutchim VARCHAR(255) NOT NULL, annee VARCHAR(4) NOT NULL, adhesion DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_C6E005B74A00ED8D (reference_ajeutchim), INDEX IDX_C6E005B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_montantannuelles (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_4D7CDFD9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_postajeutchims (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_E2B239E2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_presidents (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, membre_id INT NOT NULL, debuted_at DATETIME DEFAULT NULL, fined_at DATETIME DEFAULT NULL, contenue LONGTEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, etat INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_B8527A6A76ED395 (user_id), INDEX IDX_B8527A66A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_rejectproject (id INT AUTO_INCREMENT NOT NULL, depense_id INT NOT NULL, user_id INT NOT NULL, motif LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_1D5796041D81563 (depense_id), INDEX IDX_1D57960A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, pseudo VARCHAR(255) DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CC20AAB4E7927C74 (email), UNIQUE INDEX UNIQ_CC20AAB412B2DC9C (matricule), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_versements (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, objet INT NOT NULL, description LONGTEXT DEFAULT NULL, prenom VARCHAR(255) NOT NULL, an VARCHAR(4) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_videos (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_3CA8EA74A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_village (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, description LONGTEXT NOT NULL, histoire LONGTEXT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_D1C69981A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ajeutchim_votants (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_3B02CDFCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annee (id INT AUTO_INCREMENT NOT NULL, annee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Ajeutchim_adhesions ADD CONSTRAINT FK_AF6C5EC9A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_ajeutchim ADD CONSTRAINT FK_B57BAEF5A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_annuellecotisations ADD CONSTRAINT FK_5FBED0816A99F74A FOREIGN KEY (membre_id) REFERENCES Ajeutchim_membres (id)');
        $this->addSql('ALTER TABLE Ajeutchim_apropos ADD CONSTRAINT FK_BBEA1881A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_articles ADD CONSTRAINT FK_13FE62CEA76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_articles ADD CONSTRAINT FK_13FE62CEBCF5E72D FOREIGN KEY (categorie_id) REFERENCES Ajeutchim_categories (id)');
        $this->addSql('ALTER TABLE Ajeutchim_bureaux ADD CONSTRAINT FK_8F6999DF6A99F74A FOREIGN KEY (membre_id) REFERENCES Ajeutchim_membres (id)');
        $this->addSql('ALTER TABLE Ajeutchim_bureaux ADD CONSTRAINT FK_8F6999DFEE137E2C FOREIGN KEY (post_ajeutchim_id) REFERENCES Ajeutchim_postajeutchims (id)');
        $this->addSql('ALTER TABLE Ajeutchim_bureaux ADD CONSTRAINT FK_8F6999DFB40A33C7 FOREIGN KEY (president_id) REFERENCES Ajeutchim_presidents (id)');
        $this->addSql('ALTER TABLE Ajeutchim_candidats ADD CONSTRAINT FK_37F1E9BB6121583 FOREIGN KEY (candidature_id) REFERENCES Ajeutchim_candidatures (id)');
        $this->addSql('ALTER TABLE Ajeutchim_categories ADD CONSTRAINT FK_30CCF16AA76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_comments ADD CONSTRAINT FK_F3BDC58C7294869C FOREIGN KEY (article_id) REFERENCES Ajeutchim_articles (id)');
        $this->addSql('ALTER TABLE Ajeutchim_cotisations ADD CONSTRAINT FK_2B1F6B876A99F74A FOREIGN KEY (membre_id) REFERENCES Ajeutchim_membres (id)');
        $this->addSql('ALTER TABLE Ajeutchim_decaissements ADD CONSTRAINT FK_6250884341D81563 FOREIGN KEY (depense_id) REFERENCES Ajeutchim_depenses (id)');
        $this->addSql('ALTER TABLE Ajeutchim_decaissements ADD CONSTRAINT FK_62508843A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_decaissements ADD CONSTRAINT FK_6250884332516FE2 FOREIGN KEY (bureau_id) REFERENCES Ajeutchim_bureaux (id)');
        $this->addSql('ALTER TABLE Ajeutchim_depenses ADD CONSTRAINT FK_42165D6DA76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_evenementRealiser ADD CONSTRAINT FK_290CE427A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_flashs ADD CONSTRAINT FK_97AADEA8A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_imageAccueils ADD CONSTRAINT FK_319DDD1DA76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_mandats ADD CONSTRAINT FK_72035C946A99F74A FOREIGN KEY (membre_id) REFERENCES Ajeutchim_membres (id)');
        $this->addSql('ALTER TABLE Ajeutchim_mandats ADD CONSTRAINT FK_72035C94EE137E2C FOREIGN KEY (post_ajeutchim_id) REFERENCES Ajeutchim_postajeutchims (id)');
        $this->addSql('ALTER TABLE Ajeutchim_membreconseils ADD CONSTRAINT FK_95F243476A99F74A FOREIGN KEY (membre_id) REFERENCES Ajeutchim_membres (id)');
        $this->addSql('ALTER TABLE Ajeutchim_membres ADD CONSTRAINT FK_C6E005B7A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_montantannuelles ADD CONSTRAINT FK_4D7CDFD9A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_postajeutchims ADD CONSTRAINT FK_E2B239E2A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_presidents ADD CONSTRAINT FK_B8527A6A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_presidents ADD CONSTRAINT FK_B8527A66A99F74A FOREIGN KEY (membre_id) REFERENCES Ajeutchim_membres (id)');
        $this->addSql('ALTER TABLE Ajeutchim_rejectproject ADD CONSTRAINT FK_1D5796041D81563 FOREIGN KEY (depense_id) REFERENCES Ajeutchim_depenses (id)');
        $this->addSql('ALTER TABLE Ajeutchim_rejectproject ADD CONSTRAINT FK_1D57960A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_videos ADD CONSTRAINT FK_3CA8EA74A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_village ADD CONSTRAINT FK_D1C69981A76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE Ajeutchim_votants ADD CONSTRAINT FK_3B02CDFCA76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES Ajeutchim_users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Ajeutchim_comments DROP FOREIGN KEY FK_F3BDC58C7294869C');
        $this->addSql('ALTER TABLE Ajeutchim_decaissements DROP FOREIGN KEY FK_6250884332516FE2');
        $this->addSql('ALTER TABLE Ajeutchim_candidats DROP FOREIGN KEY FK_37F1E9BB6121583');
        $this->addSql('ALTER TABLE Ajeutchim_articles DROP FOREIGN KEY FK_13FE62CEBCF5E72D');
        $this->addSql('ALTER TABLE Ajeutchim_decaissements DROP FOREIGN KEY FK_6250884341D81563');
        $this->addSql('ALTER TABLE Ajeutchim_rejectproject DROP FOREIGN KEY FK_1D5796041D81563');
        $this->addSql('ALTER TABLE Ajeutchim_annuellecotisations DROP FOREIGN KEY FK_5FBED0816A99F74A');
        $this->addSql('ALTER TABLE Ajeutchim_bureaux DROP FOREIGN KEY FK_8F6999DF6A99F74A');
        $this->addSql('ALTER TABLE Ajeutchim_cotisations DROP FOREIGN KEY FK_2B1F6B876A99F74A');
        $this->addSql('ALTER TABLE Ajeutchim_mandats DROP FOREIGN KEY FK_72035C946A99F74A');
        $this->addSql('ALTER TABLE Ajeutchim_membreconseils DROP FOREIGN KEY FK_95F243476A99F74A');
        $this->addSql('ALTER TABLE Ajeutchim_presidents DROP FOREIGN KEY FK_B8527A66A99F74A');
        $this->addSql('ALTER TABLE Ajeutchim_bureaux DROP FOREIGN KEY FK_8F6999DFEE137E2C');
        $this->addSql('ALTER TABLE Ajeutchim_mandats DROP FOREIGN KEY FK_72035C94EE137E2C');
        $this->addSql('ALTER TABLE Ajeutchim_bureaux DROP FOREIGN KEY FK_8F6999DFB40A33C7');
        $this->addSql('ALTER TABLE Ajeutchim_adhesions DROP FOREIGN KEY FK_AF6C5EC9A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_ajeutchim DROP FOREIGN KEY FK_B57BAEF5A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_apropos DROP FOREIGN KEY FK_BBEA1881A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_articles DROP FOREIGN KEY FK_13FE62CEA76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_categories DROP FOREIGN KEY FK_30CCF16AA76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_decaissements DROP FOREIGN KEY FK_62508843A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_depenses DROP FOREIGN KEY FK_42165D6DA76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_evenementRealiser DROP FOREIGN KEY FK_290CE427A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_flashs DROP FOREIGN KEY FK_97AADEA8A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_imageAccueils DROP FOREIGN KEY FK_319DDD1DA76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_membres DROP FOREIGN KEY FK_C6E005B7A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_montantannuelles DROP FOREIGN KEY FK_4D7CDFD9A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_postajeutchims DROP FOREIGN KEY FK_E2B239E2A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_presidents DROP FOREIGN KEY FK_B8527A6A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_rejectproject DROP FOREIGN KEY FK_1D57960A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_videos DROP FOREIGN KEY FK_3CA8EA74A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_village DROP FOREIGN KEY FK_D1C69981A76ED395');
        $this->addSql('ALTER TABLE Ajeutchim_votants DROP FOREIGN KEY FK_3B02CDFCA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE Ajeutchim_adhesions');
        $this->addSql('DROP TABLE Ajeutchim_ajeutchim');
        $this->addSql('DROP TABLE Ajeutchim_annuellecotisations');
        $this->addSql('DROP TABLE Ajeutchim_apropos');
        $this->addSql('DROP TABLE Ajeutchim_articles');
        $this->addSql('DROP TABLE Ajeutchim_bilan');
        $this->addSql('DROP TABLE Ajeutchim_bureaux');
        $this->addSql('DROP TABLE Ajeutchim_candidats');
        $this->addSql('DROP TABLE Ajeutchim_candidatures');
        $this->addSql('DROP TABLE Ajeutchim_categories');
        $this->addSql('DROP TABLE Ajeutchim_comments');
        $this->addSql('DROP TABLE Ajeutchim_contacts');
        $this->addSql('DROP TABLE Ajeutchim_cotisations');
        $this->addSql('DROP TABLE Ajeutchim_decaissements');
        $this->addSql('DROP TABLE Ajeutchim_depenses');
        $this->addSql('DROP TABLE Ajeutchim_desactives');
        $this->addSql('DROP TABLE Ajeutchim_dons');
        $this->addSql('DROP TABLE Ajeutchim_evenementRealiser');
        $this->addSql('DROP TABLE Ajeutchim_flashs');
        $this->addSql('DROP TABLE Ajeutchim_imageAccueils');
        $this->addSql('DROP TABLE Ajeutchim_mandats');
        $this->addSql('DROP TABLE Ajeutchim_membreconseils');
        $this->addSql('DROP TABLE Ajeutchim_membres');
        $this->addSql('DROP TABLE Ajeutchim_montantannuelles');
        $this->addSql('DROP TABLE Ajeutchim_postajeutchims');
        $this->addSql('DROP TABLE Ajeutchim_presidents');
        $this->addSql('DROP TABLE Ajeutchim_rejectproject');
        $this->addSql('DROP TABLE Ajeutchim_users');
        $this->addSql('DROP TABLE Ajeutchim_versements');
        $this->addSql('DROP TABLE Ajeutchim_videos');
        $this->addSql('DROP TABLE Ajeutchim_village');
        $this->addSql('DROP TABLE Ajeutchim_votants');
        $this->addSql('DROP TABLE annee');
        $this->addSql('DROP TABLE reset_password_request');
    }
}

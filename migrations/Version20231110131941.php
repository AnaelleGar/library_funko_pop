<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Admin;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110131941 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'First Migration Of The SUPER ADMIN ANAELLE';
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, is_super_administrator TINYINT(1) NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, reset_password_token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figurine (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, wishlist_id INT DEFAULT NULL, collection_id INT DEFAULT NULL, mycollection_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_9DD6478C35E566A (family_id), INDEX IDX_9DD6478FB8E54CD (wishlist_id), INDEX IDX_9DD6478514956FD (collection_id), INDEX IDX_9DD647873E76872 (mycollection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE my_collection (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_BC96361A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, mycollection_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64973E76872 (mycollection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_9CE12A31A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE figurine ADD CONSTRAINT FK_9DD6478C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('ALTER TABLE figurine ADD CONSTRAINT FK_9DD6478FB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlist (id)');
        $this->addSql('ALTER TABLE figurine ADD CONSTRAINT FK_9DD6478514956FD FOREIGN KEY (collection_id) REFERENCES my_collection (id)');
        $this->addSql('ALTER TABLE figurine ADD CONSTRAINT FK_9DD647873E76872 FOREIGN KEY (mycollection_id) REFERENCES my_collection (id)');
        $this->addSql('ALTER TABLE my_collection ADD CONSTRAINT FK_BC96361A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64973E76872 FOREIGN KEY (mycollection_id) REFERENCES my_collection (id)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUp(Schema $schema): void
    {
        /* @var EntityManager $manager */
        $manager = $this->container->get('doctrine')->getManager();
        $encoder = $this->container->get('security.user_password_hasher');

        $admin = (new Admin())
            ->setEmail('garnier.anaelle35@gmail.com')
            ->setFirstName('Anaëlle')
            ->setLastName('Garnier')
            ->setIsSuperAdministrator(true)
            ->setRoles([]);
        $admin->setPassword($encoder->hashPassword($admin, 'LALALALA'));

        $manager->persist($admin);
        $manager->flush();
    }

    /**
     * @param Schema $schema
     *
     * @return void
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function preDown(Schema $schema): void
    {
        /** @var EntityManager $manager */
        $manager = $this->container->get('doctrine')->getManager();
        $administrators = $manager->getRepository(Admin::class)->findAll();
        if (null !== $administrators) {
            foreach ($administrators as $administrator) {
                $manager->remove($administrator);
            }
            $manager->flush();
        }
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figurine DROP FOREIGN KEY FK_9DD6478C35E566A');
        $this->addSql('ALTER TABLE figurine DROP FOREIGN KEY FK_9DD6478FB8E54CD');
        $this->addSql('ALTER TABLE figurine DROP FOREIGN KEY FK_9DD6478514956FD');
        $this->addSql('ALTER TABLE figurine DROP FOREIGN KEY FK_9DD647873E76872');
        $this->addSql('ALTER TABLE my_collection DROP FOREIGN KEY FK_BC96361A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64973E76872');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A31A76ED395');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE figurine');
        $this->addSql('DROP TABLE my_collection');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

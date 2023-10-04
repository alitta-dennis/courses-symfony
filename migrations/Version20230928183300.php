<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928183300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_cart (course_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_C30155B1591CC992 (course_id), INDEX IDX_C30155B11AD5CDBF (cart_id), PRIMARY KEY(course_id, cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_cart ADD CONSTRAINT FK_C30155B1591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_cart ADD CONSTRAINT FK_C30155B11AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491AD5CDBF ON user (cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_cart DROP FOREIGN KEY FK_C30155B1591CC992');
        $this->addSql('ALTER TABLE course_cart DROP FOREIGN KEY FK_C30155B11AD5CDBF');
        $this->addSql('DROP TABLE course_cart');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491AD5CDBF');
        $this->addSql('DROP INDEX UNIQ_8D93D6491AD5CDBF ON user');
        $this->addSql('ALTER TABLE user DROP cart_id');
    }
}

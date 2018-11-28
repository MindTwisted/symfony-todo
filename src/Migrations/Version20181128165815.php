<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181128165815 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE todos ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE todos ADD CONSTRAINT FK_CD826255A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_CD826255A76ED395 ON todos (user_id)');
        $this->addSql('ALTER TABLE categories ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_3AF34668A76ED395 ON categories (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668A76ED395');
        $this->addSql('DROP INDEX IDX_3AF34668A76ED395 ON categories');
        $this->addSql('ALTER TABLE categories DROP user_id');
        $this->addSql('ALTER TABLE todos DROP FOREIGN KEY FK_CD826255A76ED395');
        $this->addSql('DROP INDEX IDX_CD826255A76ED395 ON todos');
        $this->addSql('ALTER TABLE todos DROP user_id');
    }
}

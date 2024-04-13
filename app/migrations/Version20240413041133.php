<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413041133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f09de18e50b');
        $this->addSql('DROP INDEX idx_52ea1f09de18e50b');
        $this->addSql('ALTER TABLE order_item ADD order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE order_item RENAME COLUMN product_id_id TO product_id');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        $this->addSql('ALTER TABLE "order" ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON "order" (customer_id)');
//        $this->addSql('ALTER TABLE product ADD key VARCHAR(255) NOT NULL DEFAULT \'def\'');
        $this->addSql('ALTER TABLE product ADD key VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD8A90ABA9 ON product (key)');
        $this->addSql('ALTER TABLE order_item DROP url');
        $this->addSql('ALTER TABLE "order" ADD updated_at DATE NOT NULL');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN create_date TO created_at');
        $this->addSql('ALTER TABLE order_item DROP url');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('DROP INDEX IDX_52EA1F094584665A');
        $this->addSql('DROP INDEX IDX_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item ADD product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item DROP product_id');
        $this->addSql('ALTER TABLE order_item DROP order_id');
        $this->addSql('ALTER TABLE order_item DROP quantity');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f09de18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_52ea1f09de18e50b ON order_item (product_id_id)');
        $this->addSql('ALTER TABLE product DROP key');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989395C3F3');
        $this->addSql('DROP INDEX IDX_F52993989395C3F3');
        $this->addSql('ALTER TABLE "order" DROP customer_id');
        $this->addSql('DROP INDEX UNIQ_D34A04AD8A90ABA9');
        $this->addSql('ALTER TABLE order_item ADD url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD create_date DATE NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP created_at');
        $this->addSql('ALTER TABLE "order" DROP updated_at');
        $this->addSql('ALTER TABLE order_item ADD url VARCHAR(255) NOT NULL');
    }
}

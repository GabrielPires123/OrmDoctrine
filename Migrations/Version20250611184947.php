<?php

declare(strict_types=1);

namespace ORM\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611184947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
       $table = $schema->createTable('Usuario');
        $table->addColumn('id','integer')->getAutoincrement(true);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
       $schema->dropTable('Usuario');

    }
}

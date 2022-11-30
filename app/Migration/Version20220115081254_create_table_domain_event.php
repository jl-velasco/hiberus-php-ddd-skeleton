<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220115081254_create_table_domain_event extends AbstractMigration
{
    private const TABLE_NAME = 'domain_event';

    public function getDescription(): string
    {
        return 'Migration for table domina events';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'string', ['notnull' => true]);
        $table->addColumn('aggregate_id', 'string', ['notnull' => true]);
        $table->addColumn('name', 'string', ['notnull' => true]);
        $table->addColumn('body', 'json', ['notnull' => true]);
        $table->addColumn('occurred_on', 'timestamp', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}

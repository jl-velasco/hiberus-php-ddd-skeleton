<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230512122154_create_table_events extends AbstractMigration
{
    private const TABLE_NAME = 'events';

    public function getDescription(): string
    {
        return 'Migration for table events';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);

        $table->addColumn('event_id', 'guid', ['length' => 36]);
        $table->addColumn('agreggate_id', 'guid', ['length' => 36]);
        $table->addColumn('playhead', 'integer', ['unsigned' => true]);
        $table->addColumn('payload', 'text');
        $table->addColumn('recorded_on', 'string', ['length' => 32]);
        $table->addColumn('type', 'string', ['length' => 255]);

        $table->setPrimaryKey(['event_id']);
        $table->addUniqueIndex(['agreggate_id', 'playhead']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}

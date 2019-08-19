<?php

namespace Rival\MviewFix\Magento\Framework\Mview\View;

class Changelog extends \Magento\Framework\Mview\View\Changelog
{
    /**
     * Create changelog table
     *
     * @return void
     * @throws \Exception
     */
    public function create()
    {
        $changelogTableName = $this->resource->getTableName($this->getName());
        $indexName = $this->connection->getIndexName(
            $changelogTableName,
            [$this->getColumnName()],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        );

        if (!$this->connection->isTableExists($changelogTableName)) {
            $table = $this->connection->newTable(
                $changelogTableName
            )->addColumn(
                'version_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Version ID'
            )->addColumn(
                $this->getColumnName(),
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            );
            $this->connection->createTable($table);
        }

        $this->connection->dropIndex($changelogTableName, $indexName);
        if ($changelogTableName == 'algolia_products_cl') {
            $this->connection->addIndex(
                $changelogTableName,
                $indexName,
                [$this->getColumnName()],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            );
        }
    }
}
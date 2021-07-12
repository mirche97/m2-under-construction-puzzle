<?php

/**
 * Copyright © 2021 mirche (https://github.com/mirche97)| All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mirche\UCPuzzle\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    const PUZZLE_TABLE_NAME = 'ucpuzzle_puzzle';
    const PIECE_TABLE_NAME = 'ucpuzzle_piece';
    const TASK_TABLE_NAME = 'ucpuzzle_task';

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // TODO: Implement install() method.
        $setup->startSetup();

        $this->createPuzzleTable($setup);
        $this->createPieceTable($setup);
        $this->createTaskTable($setup);

        $setup->endSetup();
    }

    /**
     * @throws \Zend_Db_Exception
     */
    protected function createPuzzleTable(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::PUZZLE_TABLE_NAME))
            ->addColumn(
                'puzzle_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'width',
                Table::TYPE_SMALLINT,
                null,
                ['default' => 800, 'nullable' => false],
                'Width (px)'
            )
            ->addColumn(
                'height',
                Table::TYPE_SMALLINT,
                null,
                ['default' => 500, 'nullable' => false],
                'Height (px)'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Stores'
            );

        $installer->getConnection()->createTable($table);
    }

    protected function createPieceTable(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::PIECE_TABLE_NAME))
            ->addColumn(
                'piece_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'type',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => true],
                'Type (corner, side or middle)'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => true],
                'Textual identifier)'
            )
            ->addColumn(
                'top',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Top side'
            )
            ->addColumn(
                'right',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Right side'
            )
            ->addColumn(
                'bottom',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Bottom side'
            )
            ->addColumn(
                'left',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Left side'
            );

        $installer->getConnection()->createTable($table);
    }

    protected function createTaskTable(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::TASK_TABLE_NAME))
            ->addColumn(
                'task_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'puzzle_id',
                Table::TYPE_INTEGER,
                null,
                ['default' => 0, 'nullable' => false],
                'Puzzle Id'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Stores'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Task name'
            )
            ->addColumn(
                'show_name',
                Table::TYPE_BOOLEAN,
                null,
                ['default' => 1, 'nullable' => false],
                'Show Task Name'
            )
            ->addColumn(
                'order',
                Table::TYPE_SMALLINT,
                null,
                ['default' => null, 'nullable' => true],
                'Order'
            )
            ->addColumn(
                'finished',
                Table::TYPE_BOOLEAN,
                null,
                ['default' => 0, 'nullable' => false],
                'Is task finished'
            )
            ->addColumn(
                'description',
                Table::TYPE_TEXT,
                null,
                ['default' => null, 'nullable' => true],
                'Task name'
            )
            ->addColumn(
                'due_date',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Task Due Date (optional)'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Form Creation Time'
            );

        $installer->getConnection()->createTable($table);
    }
}

<?php

/**
 * Copyright © 2021 mirche (https://github.com/mirche97)| All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mirche\UCPuzzle\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    const PUZZLE_TABLE_NAME = 'ucpuzzle_puzzle';
    const TASK_TABLE_NAME = 'ucpuzzle_task';

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // TODO: Implement install() method.
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->updatePuzzleTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->updateTaskTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $this->addStartWith($setup);
        }

        if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $this->addPieceIdToTask($setup);
        }

        $setup->endSetup();
    }

    /**
     * @throws \Zend_Db_Exception
     */
    protected function updatePuzzleTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $connection->changeColumn(
            $setup->getConnection()->getTableName(self::PUZZLE_TABLE_NAME),
            'width',
            'rows',
            [
                'type' => Table::TYPE_INTEGER,
                'default' => 4,
                'nullable' => false,
                'comment' => 'Rows count',
            ],
        );
        $connection->changeColumn(
            $setup->getConnection()->getTableName(self::PUZZLE_TABLE_NAME),
            'height',
            'columns',
            [
                'type' => Table::TYPE_INTEGER,
                'default' => 5,
                'nullable' => false,
                'comment' => 'Columns count',
            ],
        );
    }

    protected function updateTaskTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $connection->addColumn(
            $setup->getConnection()->getTableName(self::TASK_TABLE_NAME),
            'row',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Row',
                'after' => 'order'
            ]
        );
        $connection->addColumn(
            $setup->getConnection()->getTableName(self::TASK_TABLE_NAME),
            'column',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Row',
                'after' => 'row'
            ]
        );
        $connection->addColumn(
            $setup->getConnection()->getTableName(self::TASK_TABLE_NAME),
            'path',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'SVG Path',
                'after' => 'column'
            ]
        );
    }

    protected function addStartWith(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $connection->addColumn(
            $setup->getConnection()->getTableName(self::PUZZLE_TABLE_NAME),
            'start_with',
            [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => false,
                'comment' => 'Top Left Corner Right Side  - tab / blank',
                'default' => 1
            ]
        );
    }

    protected function addPieceIdToTask(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $connection->addColumn(
            $setup->getConnection()->getTableName(self::TASK_TABLE_NAME),
            'piece_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Piece ID',
            ]
        );
    }
}

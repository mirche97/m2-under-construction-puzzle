<?php

namespace Mirche\UCPuzzle\Model\ResourceModel\Puzzle;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'puzzle_id';
    protected $_eventPrefix = 'mirche_ucpuzzle_puzzle_collection';
    protected $_eventObject = 'puzzle_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirche\UCPuzzle\Model\Puzzle', 'Mirche\UCPuzzle\Model\ResourceModel\Puzzle');
    }
}

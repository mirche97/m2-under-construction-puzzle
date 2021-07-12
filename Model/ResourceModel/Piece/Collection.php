<?php

namespace Mirche\UCPuzzle\Model\ResourceModel\Piece;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'piece_id';
    protected $_eventPrefix = 'mirche_ucpuzzle_piece_collection';
    protected $_eventObject = 'piece_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirche\UCPuzzle\Model\Piece', 'Mirche\UCPuzzle\Model\ResourceModel\Piece');
    }
}

<?php

namespace Mirche\UCPuzzle\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Puzzle extends AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('ucpuzzle_puzzle', 'puzzle_id');
    }
}

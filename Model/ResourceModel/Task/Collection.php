<?php

namespace Mirche\UCPuzzle\Model\ResourceModel\Task;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'task_id';
    protected $_eventPrefix = 'mirche_ucpuzzle_task_collection';
    protected $_eventObject = 'task_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirche\UCPuzzle\Model\Task', 'Mirche\UCPuzzle\Model\ResourceModel\Task');
    }

    public function getMaxColumn(): ?int
    {
        return $this->setOrder('`column`', self::SORT_ORDER_DESC)->fetchItem()->getData('column');
    }

    public function getMaxRow(): ?int
    {
        return $this->setOrder('`row`', self::SORT_ORDER_DESC)->fetchItem()->getData('row');
    }
}

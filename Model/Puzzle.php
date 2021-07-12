<?php

namespace Mirche\UCPuzzle\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Mirche\UCPuzzle\Model\ResourceModel\Task\Collection as TaskCollection;

class Puzzle extends AbstractModel implements IdentityInterface
{

    const CACHE_TAG = 'mirche_ucpuzzle_puzzle';

    protected $_cacheTag = 'mirche_ucpuzzle_puzzle';

    protected $_eventPrefix = 'mirche_ucpuzzle_puzzle';

    protected  \Mirche\UCPuzzle\Model\ResourceModel\Task\CollectionFactory $_taskCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Mirche\UCPuzzle\Model\ResourceModel\Task\CollectionFactory $_taskCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_taskCollectionFactory = $_taskCollectionFactory;
    }

    protected function _construct()
    {
        $this->_init('Mirche\UCPuzzle\Model\ResourceModel\Puzzle');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getRows(): int
    {
        return $this->getData('rows');
    }

    public function getColumns(): int
    {
        return $this->getData('columns');
    }

    public function getStartWith(): bool
    {
        return (bool)$this->getData('start_with');
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    public function getTasksCollection(): TaskCollection
    {
        $collection = $this->_taskCollectionFactory->create()->addFieldToFilter('puzzle_id', $this->getId());

        return $collection;
    }
}

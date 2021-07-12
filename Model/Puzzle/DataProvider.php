<?php

namespace Mirche\UCPuzzle\Model\Puzzle;

use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected array $loadedData;

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        \Mirche\UCPuzzle\Model\ResourceModel\Puzzle\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [])
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];
        foreach ($items as $puzzle) {
            $this->loadedData[$puzzle->getId()]['puzzle'] = $puzzle->getData();
        }

        return $this->loadedData;
    }
}

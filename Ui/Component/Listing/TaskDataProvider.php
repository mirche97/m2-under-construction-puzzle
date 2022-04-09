<?php

namespace Mirche\UCPuzzle\Ui\Component\Listing;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class TaskDataProvider extends DataProvider
{
    public function getData()
    {
        /** @var SearchResultInterface $collection */
        $collection = $this->getSearchResult();

        return $this->searchResultToOutput($collection);
    }
}

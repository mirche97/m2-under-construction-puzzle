<?php

namespace Mirche\UCPuzzle\Block\Adminhtml\Buttons\Puzzle;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Mirche\UCPuzzle\Block\Adminhtml\Buttons\GenericButton;
use Mirche\UCPuzzle\Model\Puzzle;

class DefaultTasksButton extends GenericButton implements ButtonProviderInterface
{

    protected \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory;
    protected \Mirche\UCPuzzle\Model\ResourceModel\Puzzle\CollectionFactory $puzzleCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory,
        \Mirche\UCPuzzle\Model\ResourceModel\Puzzle\CollectionFactory $puzzleCollectionFactory
    ) {
        parent::__construct($context);
        $this->puzzleFactory = $puzzleFactory;
        $this->puzzleCollectionFactory = $puzzleCollectionFactory;
    }

    public function getButtonData(): array
    {

        $puzzleId = $this->getCurrentId();
        /** @var Puzzle $puzzle */
        $puzzle = $this->puzzleCollectionFactory->create()->addFieldToFilter('puzzle_id', $puzzleId)->load()->fetchItem();
        if (!$puzzle) {
            return [];
        }

        $tasks = $puzzle->getTasksCollection();
        if ($tasks->count() === 0) {
            $label = 'Create default tasks';
        } else {
            $label = 'Update default tasks';
        }

        $url = $this->getUrl('*/*/defaultTasks', ['id' => $this->getCurrentId()]);
        return [
            'label' => __($label),
            'class' => 'save primary',
            'on_click' => sprintf("location.href = '%s';", $url),
            'sort_order' => 90,
        ];
    }
}

<?php

namespace Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\PageFactory;
use Mirche\UCPuzzle\Helper\Draw as DrawHelper;
use Mirche\UCPuzzle\Controller\Adminhtml\Puzzle as PuzzleAction;
use Mirche\UCPuzzle\Model\Puzzle as PuzzleModel;
use Mirche\UCPuzzle\Model\ResourceModel\Task\Collection as TaskCollection;
use Mirche\UCPuzzle\Model\Task;

class DefaultTasks extends PuzzleAction
{
    protected \Mirche\UCPuzzle\Model\TaskFactory $taskFactory;

    protected PuzzleModel $puzzle;

    protected TaskCollection $taskCollection;

    protected \Mirche\UCPuzzle\Model\ResourceModel\Puzzle\CollectionFactory $puzzleCollectionFactory;

    protected DrawHelper $drawHelper;

    protected array $deafultTasks = [
        1 => 'DomainName',
        2 => 'Chamber of Commerce registration',
        3 => 'Products',
        4 => 'Categories',
        5 => 'Prices',
        6 => 'Payment',
        7 => 'Invoice',
        8 => 'Shipping',
        9 => 'Terms of Conditions',
        10 => 'Privacy Policy',
        11 => 'Cookie policy',
        12 => 'Mailing',
        13 => 'About us',
        14 => 'Contact',
        15 => 'RMA',
        16 => 'SEO'
    ];

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Mirche\UCPuzzle\Model\ResourceModel\Puzzle\CollectionFactory $puzzleCollectionFactory,
        \Mirche\UCPuzzle\Model\TaskFactory $taskFactory,
        DrawHelper $drawHelper
    )
    {
        parent::__construct($context, $resultPageFactory);
        $this->taskFactory = $taskFactory;
        $this->puzzleCollectionFactory = $puzzleCollectionFactory;
        $this->drawHelper = $drawHelper;
    }

    public function execute()
    {
        $puzzleId = $this->getRequest()->getParam('id');
        /** @var \Mirche\UCPUzzle\Model\Puzzle $puzzle */
        $this->puzzle = $this->puzzleCollectionFactory->create()->addFieldToFilter('puzzle_id', $puzzleId)->load()->fetchItem();
        $this->taskCollection = $this->puzzle->getTasksCollection();

        if ($this->taskCollection->count() === 0) {
            $this->createTasks();
        } else {
            $this->updateTasks();
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('ucpuzzle/puzzle/edit', ['id' => $this->getRequest()->getParam('id')]);

        return $resultRedirect;
    }

    protected function createTasks(): void
    {
        $defaultTaskData = [
            'store_id' => $this->puzzle->getData('store_id'),
            'puzzle_id' => $this->puzzle->getData('puzzle_id'),
        ];

        $maxRows = $this->puzzle->getRows();
        $maxCols = $this->puzzle->getColumns();

        for ($row = 1; $row <= $maxRows; $row++) {
            for ($col = 1; $col <= $maxCols; $col++) {
                $idx = ($row - 1) * $maxCols + $col;

                if (!isset($this->deafultTasks[$idx])) {
                    break;
                }

                $taskData = [
                    'name' => $this->deafultTasks[$idx],
                    'row' => $row,
                    'column' => $col,
                    'order' => $idx,
                    'path' => '',
                ];

                $task = $this->taskFactory->create();
                $task->addData(array_merge($defaultTaskData, $taskData));

                $path = $this->drawHelper->drawPath($task, $this->puzzle);
                $task->setPath($path)->save();
            }
        }
    }

    protected function updateTasks(): void
    {
        $maxColumn = $this->puzzle->getTasksCollection()->getMaxColumn();
        $maxRow = $this->puzzle->getTasksCollection()->getMaxRow();

        if ($maxRow !== $this->puzzle->getRows() || $maxColumn !== $this->puzzle->getColumns()) {
            $this->updateRowsEnColumns();
        }
        /** @var Task $task */
        foreach ($this->puzzle->getTasksCollection() as $task) {
            $path = $this->drawHelper->drawPath($task, $this->puzzle);
            $task->setPath($path)->save();
        }
    }

    protected function updateRowsEnColumns(): void
    {
        $maxRows = $this->puzzle->getRows();
        $maxCols = $this->puzzle->getColumns();

        for ($row = 1; $row <= $maxRows; $row++) {
            for ($col = 1; $col <= $maxCols; $col++) {
                $idx = ($row - 1) * $maxCols + $col;

                /** @var Task $task */
                $task = $this->puzzle->getTasksCollection()->addFieldToFilter('order', $idx)->fetchItem();
                if ($task) {
                    $task->setRow($row)->setColumn($col)->save();
                }
            }
        }
    }
}

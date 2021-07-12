<?php

namespace Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

use Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

class NewAction extends Puzzle
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create(); // loads and renders default layout

        $resultPage->getConfig()->getTitle()->prepend(__('UCPuzzle'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Puzzle'));

        return $resultPage;
    }
}

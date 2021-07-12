<?php

namespace Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

use Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

class Edit extends Puzzle
{
    public function execute()
    {
              /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create(); // loads and renders default layout

        $resultPage->getConfig()->getTitle()->prepend(__('UCPuzzle'));
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Puzzle'));

        return $resultPage;
    }
}

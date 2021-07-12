<?php

namespace Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

use Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

class Index extends Puzzle
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Puzzle'));

        return $resultPage;
    }
}

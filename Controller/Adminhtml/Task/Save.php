<?php

namespace Mirche\UCPuzzle\Controller\Adminhtml\Task;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

class Save extends Puzzle implements HttpPostActionInterface
{
    private StoreManagerInterface $storeManager;

    private \Mirche\UCPuzzle\Model\TaskFactory $taskFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StoreManagerInterface $storeManager,
        \Mirche\UCPuzzle\Model\TaskFactory $taskFactory
    )
    {
        parent::__construct($context, $resultPageFactory);
        $this->storeManager = $storeManager;
        $this->taskFactory = $taskFactory;
    }

    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $data = $this->getRequest()->getPostValue();

        try {
            if ($data) {

                $taskInstance = $this->taskFactory->create();
                $taskInstance->setData($data['task'])->save();
                $this->messageManager->addSuccessMessage(__("Data Saved Successfully."));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e, __("We can\'t submit your request, Please try again."));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('ucpuzzle/puzzle/edit', ['id' => $taskInstance->getPuzzle()->getId()]);

        return $resultRedirect;
    }
}

<?php

namespace Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Mirche\UCPuzzle\Controller\Adminhtml\Puzzle;

class Save extends Puzzle implements HttpPostActionInterface
{
    private StoreManagerInterface $storeManager;

    private \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StoreManagerInterface $storeManager,
        \Mirche\UCPuzzle\Model\PuzzleFactory $puzzleFactory
    )
    {
        parent::__construct($context, $resultPageFactory);
        $this->storeManager = $storeManager;
        $this->puzzleFactory = $puzzleFactory;
    }

    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $data = $this->getRequest()->getPostValue();

        try {
            if ($data) {
                $data['puzzle']['store_id'] = $storeId;
                $puzzleInstance = $this->puzzleFactory->create();
                $puzzleInstance->setData($data['puzzle'])->save();
                $this->messageManager->addSuccessMessage(__("Data Saved Successfully."));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e, __("We can\'t submit your request, Please try again."));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (isset($puzzleInstance) && $puzzleInstance->getId()) {
            $resultRedirect->setPath('ucpuzzle/puzzle/defaulttasks', ['id' => $puzzleInstance->getId()]);
        } else {
            $resultRedirect->setPath('ucpuzzle/puzzle');
        }

        return $resultRedirect;
    }
}

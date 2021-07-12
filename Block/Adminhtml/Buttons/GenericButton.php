<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mirche\UCPuzzle\Block\Adminhtml\Buttons;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

/**
 * Class GenericButton
 * @package Magento\Customer\Block\Adminhtml\Edit
 */
class GenericButton
{
    protected UrlInterface $urlBuilder;

    protected RequestInterface $request;

    /**
     * Constructor
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->request = $context->getRequest();
    }

    public function getCurrentId(): ?int
    {
        return $this->request->getParam('id');
    }

    /**
     * Generate url by route and parameters
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}

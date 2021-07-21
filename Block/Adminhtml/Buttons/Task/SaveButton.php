<?php

namespace Mirche\UCPuzzle\Block\Adminhtml\Buttons\Task;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Mirche\UCPuzzle\Block\Adminhtml\Buttons\GenericButton;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Task'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'ucpuzzle_task_form.ucpuzzle_task_form',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'sort_order' => 90,
        ];
    }
}

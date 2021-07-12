<?php

namespace Mirche\UCPuzzle\Block\Adminhtml\Buttons\Puzzle;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Mirche\UCPuzzle\Block\Adminhtml\Buttons\GenericButton;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Puzzle'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'ucpuzzle_puzzle_form.ucpuzzle_puzzle_form',
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

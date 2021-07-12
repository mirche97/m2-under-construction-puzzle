<?php

namespace Mirche\UCPuzzle\Model\Adminhtml\Source\Piece;

class Edge implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'flat',
                'label' => __('Flat'),
            ],
            [
                'value' => 'tab',
                'label' => __('Tab'),
            ],
            [
                'value' => 'blank',
                'label' => __('Blank'),
            ],
        ];
    }
}

<?php

namespace Mirche\UCPuzzle\Model\Adminhtml\Source\Piece;

class Type implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'corner',
                'label' => __('Corner'),
            ],
            [
                'value' => 'side',
                'label' => __('Side'),
            ],
            [
                'value' => 'middle',
                'label' => __('Middle'),
            ],
        ];
    }
}

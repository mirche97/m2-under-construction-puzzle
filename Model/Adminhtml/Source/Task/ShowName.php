<?php

namespace Mirche\UCPuzzle\Model\Adminhtml\Source\Task;

class ShowName implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '0',
                'label' => __('No')
            ],
            [
                'value' => '1',
                'label' => __('YEs'),
            ],
        ];
    }
}

<?php

namespace Mirche\UCPuzzle\Model\Adminhtml\Source\Task;

class Finished implements \Magento\Framework\Data\OptionSourceInterface
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
                'label' => __('Yes'),
            ],
        ];
    }
}

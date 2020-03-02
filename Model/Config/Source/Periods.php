<?php

namespace Orangecat\CustomerLoginHistory\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Periods implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('1 month')],
            ['value' => 2, 'label' => __('2 months')],
            ['value' => 3, 'label' => __('3 months')],
            ['value' => 6, 'label' => __('6 months')],
            ['value' => 12, 'label' => __('1 Year')]
        ];
    }
}

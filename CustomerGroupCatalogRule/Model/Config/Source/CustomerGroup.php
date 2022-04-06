<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Model\Config\Source;

/**
 * Customer list
 */
class CustomerGroup implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $customerGroup;

    /**
     * @var
     */
    protected $options;

    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     */
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
    )
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => __('Please select'), 'value' => '']];
            $customerGroup = $this->customerGroup->toOptionArray();
            unset($customerGroup[0]);
            foreach ($customerGroup as $item) {
                $this->options[] = [
                    'label' => $item['label'],
                    'value' => $item['value'],
                ];
            }
        }

        return $this->options;
    }
}

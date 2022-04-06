<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Model\Config\Source;

/**
 * ActiveOnForbid list
 *
 */
class ActionOnForbid implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => __('Show 404 page')
            ],
            [
                'value' => 1,
                'label' => __('Redirect to CMS page')
            ]
        ];
    }
}

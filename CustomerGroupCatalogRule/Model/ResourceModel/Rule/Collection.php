<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Tigren\CustomerGroupCatalogRule\Model\Rule', 'Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule');
    }
}

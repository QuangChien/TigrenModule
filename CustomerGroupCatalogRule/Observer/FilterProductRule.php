<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;

class FilterProductRule implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog
     */
    protected $ruleHelper;


    /**
     * @param \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog
     */
    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog
    )
    {
        $this->ruleHelper = $getRuleCatalog;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $collection = $observer->getData('collection');
        $hideProductStatus = $this->ruleHelper->isHide('hide_product_status');
        if ($hideProductStatus) {
            $collection->addAttributeToFilter('sku', array('nin' => $this->ruleHelper->getEntityInRule('product_select')));
        }
    }
}

<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;


class FilterCategoryRule implements \Magento\Framework\Event\ObserverInterface
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

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $collection = $observer->getData('category_collection');
        $collection->addFieldToFilter('entity_id', array('nin' => $this->ruleHelper->getEntityInRule('category_hide')));
    }
}

<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Model\Config\Source;

/**
 * Product All
 */
class ProductAll implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    protected $options;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [['label' => __('Please select'), 'value' => '']];
            $collection = $this->_productCollectionFactory->create();
            $collection->addAttributeToSelect(['name', 'sku']);
//            $collection->setPageSize(3); // fetching only 3 products
            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $item->getData('name'),
                    'value' => $item->getData('sku'),
                ];
            }
        }

        return $this->options;
    }

}

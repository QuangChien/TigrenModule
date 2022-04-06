<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Block\Catalog\Product\View\AddTo;

/**
 * Product view compare block
 *
 * @api
 * @since 101.0.1
 */
class Compare extends \Tigren\CustomerGroupCatalogRule\Block\Catalog\Product\View
{

    /**
     * Return compare params
     *
     * @return string
     * @since 101.0.1
     */
    public function getPostDataParams()
    {
        $product = $this->getProduct();
        return $this->_compareProduct->getPostDataParams($product);
    }

    /**
     * @return bool
     */
    public function isHideAddToCompare()
    {
            return parent::isHideAddToCompare();
    }
}

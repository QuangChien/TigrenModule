<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Pricing;

use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Render as PricingRender;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Catalog Price Render
 *
 * @api
 * @method string getPriceRender()
 * @method string getPriceTypeCode()
 * @since 100.0.2
 */
class Render extends Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;


    /**
     * @var Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog
     */
    protected $ruleHelper;

    /**
     * Construct
     *
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->ruleHelper = $getRuleCatalog;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isHideProduct()
    {
        return $this->ruleHelper->isHide('hide_product_status');
    }

    /**
     * @return bool
     */
    public function isHidePrice()
    {
        return $this->ruleHelper->isHide('hide_product_price_status');
    }


    /**
     * @return bool
     */
    public function isHideAddToCart()
    {
        return $this->ruleHelper->isHide('hide_add_to_cart_status');
    }

    /**
     * @return bool
     */
    public function isHideAddToWishList()
    {
        return $this->ruleHelper->isHide('hide_add_to_wishlist_status');
    }

    /**
     * @return bool
     */
    public function isHideAddToCompare()
    {
        return $this->ruleHelper->isHide('hide_add_to_compare_status');
    }

    /**
     * @return array|false|string[]
     */
    public function getProductInRule()
    {
        return $this->ruleHelper->getEntityInRule('product_select');
    }

    /**
     * @return string
     */
    public function getProductSku()
    {
        return $this->getProduct()->getSku();
    }


    /**
     * Produce and return block's html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        if(in_array($this->getProductSku(), $this->getProductInRule())){
            if($this->isHidePrice() == false){
                /** @var PricingRender $priceRender */
                $priceRender = $this->getLayout()->getBlock($this->getPriceRender());
                if ($priceRender instanceof PricingRender) {
                    $product = $this->getProduct();
                    if ($product instanceof SaleableInterface) {
                        $arguments = $this->getData();
                        $arguments['render_block'] = $this;
                        return $priceRender->render($this->getPriceTypeCode(), $product, $arguments);
                    }
                }else{
                    return parent::_toHtml();
                }
            }
        }else{
            /** @var PricingRender $priceRender */
            $priceRender = $this->getLayout()->getBlock($this->getPriceRender());
            if ($priceRender instanceof PricingRender) {
                $product = $this->getProduct();
                if ($product instanceof SaleableInterface) {
                    $arguments = $this->getData();
                    $arguments['render_block'] = $this;
                    return $priceRender->render($this->getPriceTypeCode(), $product, $arguments);
                }
            }
        }

        return parent::_toHtml();
    }

    /**
     * Returns saleable item instance
     *
     * @return Product
     */
    protected function getProduct()
    {
        $parentBlock = $this->getParentBlock();

        $product = $parentBlock && $parentBlock->getProductItem()
            ? $parentBlock->getProductItem()
            : $this->registry->registry('product');
        return $product;
    }
}

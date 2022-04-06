<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;

class OptionProductRule implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog
     */
    protected $ruleHelper;

    /**
     * @var PageRepositoryInterface
     */
    private $_cmsPage;

    /**
     * @var SearchCriteriaBuilder
     */
    private $_search;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\UrlInterface $urlInterface
     */
    protected $_urlInterface;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $response;


    /**
     * @param \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog
     * @param \Magento\Cms\Api\PageRepositoryInterface $pageRepositoryInterface
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\App\Response\Http $response
     */
    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepositoryInterface,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Response\Http $response
    )
    {
        $this->ruleHelper = $getRuleCatalog;
        $this->_cmsPage = $pageRepositoryInterface;
        $this->_search = $searchCriteriaBuilder;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->response = $response;

    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCmsPages()
    {
        $pages = [];
        foreach($this->_cmsPage->getList($this->_getSearchCriteria())->getItems() as $page) {
            $pages[] = [
                'identifier' => $page->getIdentifier(),
                'pageId' => $page->getPageId()
            ];
        }
        return $pages;
    }

    /**
     * @param $pageId
     * @return mixed|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIdentifierCmsPageRedirect($pageId)
    {
        foreach ($this->getCmsPages() as $cmsPage){
            if($cmsPage['pageId'] == $pageId ){
                return $cmsPage['identifier'];
            }
        }
        return '';
    }


    /**
     * @return \Magento\Framework\Api\SearchCriteria
     */
    protected function _getSearchCriteria()
    {
        return $this->_search->addFilter('is_active', '1')->create();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get404UrlIdentifier()
    {
        foreach ($this->getCmsPages() as $cmsPage){
            if($cmsPage['pageId'] == 1 || $cmsPage['identifier'] == 'no-route'){
                return $cmsPage['identifier'];
            }
        }
        return '';
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        $productSku = $product->getSku();
        $productInRule = $this->ruleHelper->getEntityInRule('product_select');
        $directLinkStatus = $this->ruleHelper->isHide('direct_link_status');
        $actionOnForbidStatus = $this->ruleHelper->isHide('action_on_forbid');
        $hideProductStatus = $this->ruleHelper->isHide('hide_product_status');
        $cmsPageUrl = $this->ruleHelper->getCmsPage();

        if(in_array($productSku, $productInRule)){
            if($hideProductStatus){
                if($directLinkStatus !== true){
                    if($actionOnForbidStatus == false){
                        $this->response->setRedirect($this->getBaseUrl() . $this->get404UrlIdentifier());
                    }else{
                        $this->response->setRedirect($this->getBaseUrl() . $this->getIdentifierCmsPageRedirect($cmsPageUrl));
                    }
                }
            }
        }
    }
}

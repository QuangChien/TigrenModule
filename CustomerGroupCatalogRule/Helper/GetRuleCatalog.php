<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Helper;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory;

class GetRuleCatalog extends AbstractHelper
{
    /**
     * @var CollectionFactory
     */
    protected $_ruleCollection;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeInterface
     */
    protected $_scopeConfig;

    /**
     * @param Context $context
     * @param CollectionFactory $ruleCollection
     * @param Session $customerSession
     * @param DateTime $date
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context               $context,
        CollectionFactory     $ruleCollection,
        Session               $customerSession,
        DateTime              $date,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface  $scopeConfig
    )
    {
        $this->_ruleCollection = $ruleCollection;
        $this->_customerSession = $customerSession;
        $this->date = $date;
        $this->storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function isModuleEnable()
    {
        return $this->scopeConfig->getValue('custom/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $field
     * @return bool
     */
    public function isHide($field)
    {
        if (!is_null($this->getRule())) {
            $hideField = $this->getRule()->getData($field);
            if ($hideField == 1) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @return false|string
     */
    public function getDateCurrent()
    {
        $date = $this->date->gmtDate('Y-m-d');
        return $date;
    }

    /**
     * @return DataObject|null
     * @throws NoSuchEntityException
     */
    public function getRule()
    {
        $rule = $this->_ruleCollection->create()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('time_rule_start', array(
                array('lteq' => $this->getDateCurrent()),
                array('null' => true),
            ))
            ->addFieldToFilter('time_rule_end', array(
                array('gteq' => $this->getDateCurrent()),
                array('null' => true),
            ))
            ->addFieldToFilter('customer_group', array(
                array('like' => '%/' . $this->getGroupId() . '/%'),
                array('like' => '%/' . $this->getGroupId()),
                array('like' => $this->getGroupId() . '/%'),
                array('like' => $this->getGroupId())
            ))
            ->addFieldToFilter('store_views', array(
                array('like' => '%/' . $this->getStoreId() . '/%'),
                array('like' => '%/' . $this->getStoreId()),
                array('like' => $this->getStoreId() . '/%'),
                array('like' => $this->getStoreId()),
                array('like' => 0)
            ))
            ->setOrder('priority', 'DESC')->load()
            ->getFirstItem();
        if (!$rule->isEmpty()) {
            if ($this->isModuleEnable()) {
                return $rule;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        $storeId = $this->storeManager->getStore()->getId();
        return $storeId;
    }


    /**
     * @return int|mixed|null
     */
    public function getCmsPage()
    {
        if (!is_null($this->getRule())) {
            $cmsPage = $this->getRule()->getData('cms_pages_url');
            return $cmsPage;
        }
        return 0;
}


    /**
     * @param $field
     * @return array|false|string[]
     */
    public function getEntityInRule($field)
    {
        if (!is_null($this->getRule())) {
            $entityValue = explode('/', $this->getRule()->getData($field));
            return $entityValue;
        }
        return [];
    }

    /**
     * @return int|null
     */
    public function getGroupId()
    {
        if ($this->_customerSession->isLoggedIn()) {
            $customerGroup = $this->_customerSession->getCustomer()->getGroupId();
        } else {
            $customerGroup = 0;
        }
        return $customerGroup;
    }
}

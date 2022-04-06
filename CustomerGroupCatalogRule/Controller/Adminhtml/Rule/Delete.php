<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory;
use Tigren\CustomerGroupCatalogRule\Model\RuleFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\RedirectFactory;

class Delete extends Action
{
    /**
     * @var RuleFactory
     */
    private $_rule;

    /**
     * @var Filter
     */
    private $_filter;

    /**
     * @var CollectionFactory
     */
    private $_collection;

    /**
     * @var RedirectFactory
     */
    private $_resultRedirect;


    /**
     * @param Action\Context $context
     * @param RuleFactory $ruleFactory
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param RedirectFactory $redirectFactory
     */
    public function __construct
    (
        Action\Context    $context,
        RuleFactory       $ruleFactory,
        Filter            $filter,
        CollectionFactory $collectionFactory,
        RedirectFactory   $redirectFactory
    )
    {
        parent::__construct($context);
        $this->_rule = $ruleFactory;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_resultRedirect = $redirectFactory;
    }

    /**
     * @return Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $total = 0;
        $err = 0;

        foreach ($collection->getItems() as $item) {
            $deleteField = $this->_rule->create()->load($item->getData('rule_id'));
            try {
                $deleteField->delete();
                $total++;
            } catch (LocalizedException $exception) {
                $err++;
            }
        }

        if ($total) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $total)
            );
        }

        if ($err) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $err
                )
            );
        }

        return $this->redirectToIndex();
    }

    /**
     * Redirect to Rule list
     * @return Redirect
     */
    public function redirectToIndex()
    {
        return $this->_resultRedirect->create()->setPath('customergr_catalogrule/rule/index');
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_CustomerGroupCatalogRule::customer_group_catalog');
    }
}

<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Model\Config\Source;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Option\ArrayInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CmsPages
 * @package Tigren\CustomerGroupCatalogRule\Model\Config\Source
 */
class CmsPages implements ArrayInterface
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepositoryInterface;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * CmsPages constructor.
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        try {
            $pages = $this->getCmsPageCollection();
            if ($pages instanceof LocalizedException) {
                throw $pages;
            }
            $cnt = 0;
            foreach ($pages as $page) {
                $optionArray[$cnt]['value'] = $page->getPageId();
                $optionArray[$cnt++]['label'] = $page->getTitle();
            }
        } catch (LocalizedException $e) {
            ObjectManager::getInstance()->get(LoggerInterface::class)->info($e->getMessage());
        } catch (\Exception $e) {
            ObjectManager::getInstance()->get(LoggerInterface::class)->info($e->getMessage());
        }
        return $optionArray;
    }

    /**
     * @return \Exception|PageInterface[]|LocalizedException
     */
    public function getCmsPageCollection()
    {
        $searchCriteria = $searchCriteria = $this->searchCriteriaBuilder->create();
        try {
            $collection = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
        } catch (LocalizedException $e) {
            return $e;
        }
        return $collection;
    }
}

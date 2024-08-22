<?php

declare(strict_types=1);

/**
* Digit Software Solutions.
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
*
* @category  Dss
* @package   Dss_GoToCatalog
* @author    Extension Team
* @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
*/

namespace Dss\GoToCatalog\Block\Adminhtml\Category\Edit\Button;

use Magento\Store\Model\Store;
use Magento\Framework\Registry;
use Dss\GoToCatalog\Model\Config;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Block\Adminhtml\Category\AbstractCategory;
use Magento\Catalog\Model\ResourceModel\Category\Tree as CategoryTree;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ViewInStore extends AbstractCategory implements ButtonProviderInterface
{
    /**
     * ViewInStore Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Dss\GoToCatalog\Model\Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        CategoryTree $categoryTree,
        Registry $registry,
        CategoryFactory $categoryFactory,
        protected StoreManagerInterface $storeManager,
        protected Config $config,
        array $data = []
    ) {
        parent::__construct($context, $categoryTree, $registry, $categoryFactory, $data);
    }

    /**
     * Get Button Data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->canShow()) {
            return [];
        }

        return [
            'label'      => __('View in Store'),
            'on_click'   => sprintf("window.open('%s')", $this->getCategoryUrl()),
            'target'     => '_blank',
            'sort_order' => 11
        ];
    }
    
    /**
     * Can Show
     *
     * @return void
     */
    public function canShow()
    {
        $category = $this->getCategory();
        $categoryId = $category->getId();
        $category->setStoreId($this->getCurrentStoreId());

        return $categoryId
            && !in_array($categoryId, $this->getRootIds())
            && $category->isDeleteable()
            && $this->config->isEnabled()
            && $this->config->isCategoryLinkEnabled()
            && $this->getCategory()->getUrlKey();
    }
    
    /**
     * Get Catgory Url
     *
     * @return string
     */
    private function getCategoryUrl(): string
    {
        $category = $this->getCategory()->setStoreId($this->getCurrentStoreId());
        return $category->getUrl();
    }
    
    /**
     * Get Current store id
     *
     * @return int|string
     */
    private function getCurrentStoreId(): int|string
    {
        $currentStoreId = (int) $this->storeManager->getStore()->getId();
        if ($currentStoreId === Store::DEFAULT_STORE_ID) {
            $currentStoreId = $this->storeManager->getDefaultStoreView()->getId();
        }

        return $currentStoreId;
    }
}

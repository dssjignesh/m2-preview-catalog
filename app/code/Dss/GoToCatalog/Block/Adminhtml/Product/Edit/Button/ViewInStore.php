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
namespace Dss\GoToCatalog\Block\Adminhtml\Product\Edit\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product\Url as ProductUrl;
use Dss\GoToCatalog\Model\Config;

class ViewInStore extends Generic
{
    /**
     * __construct
     *
     * @param \Magento\Framework\View\Element\UiComponent\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Dss\GoToCatalog\Model\Config $config
     * @param \Magento\Catalog\Model\Product\Url $productUrl
     */
    public function __construct(
        Context $context,
        Registry $registry,
        protected StoreManagerInterface $storeManager,
        protected Config $config,
        protected ProductUrl $productUrl
    ) {
        parent::__construct($context, $registry);
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
            'on_click'   => sprintf("window.open('%s')", $this->getProductUrl()),
            'target'     => '_blank',
            'sort_order' => 11
        ];
    }
    
    /**
     * Can Show
     *
     * @return void
     */
    private function canShow()
    {
        return !$this->getProduct()->isReadonly()
            && $this->config->isEnabled()
            && $this->config->isProductLinkEnabled()
            && $this->getProduct()->getUrlKey();
    }
    
    /**
     * Get Product Url
     *
     * @return string
     */
    private function getProductUrl(): string
    {
        $product = $this->getProduct()->setStoreId($this->getCurrentStoreId());
        return $this->productUrl->getProductUrl($product);
    }
    
    /**
     * Get Current Store id
     *
     * @return int|string
     */
    private function getCurrentStoreId(): int|string
    {
        $currentStoreId = (int)$this->storeManager->getStore()->getId();
        if ($currentStoreId === \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
            $currentStoreId = $this->storeManager->getDefaultStoreView()->getId();
        }

        return $currentStoreId;
    }
}

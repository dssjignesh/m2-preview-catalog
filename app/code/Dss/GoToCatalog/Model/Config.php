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
namespace Dss\GoToCatalog\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    public const XML_PATH_ENABLED = 'dssgotocatalog/general/enabled';
    public const XML_PATH_DEBUG = 'dssgotocatalog/general/debug';
    public const XML_PATH_ENABLE_PRODUCT_LINK = 'dssgotocatalog/catalog/enable_product_link';
    public const XML_PATH_ENABLE_CATEGORY_LINK = 'dssgotocatalog/catalog/enable_category_link';
    public const XML_PATH_CUSTOM_PRODUCT_URL_KEY = 'dssgotocatalog/catalog/product_url_key';
    
    /**
     * Config Constuctor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Get Config Flag
     *
     * @param string $xmlPath
     * @param null|int|string $storeId
     * @return string|bool
     */
    public function getConfigFlag($xmlPath, $storeId = null): string|bool
    {
        return $this->scopeConfig->isSetFlag(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Config Value
     *
     * @param string $xmlPath
     * @param null|int|string $storeId
     * @return string
     */
    public function getConfigValue($xmlPath, $storeId = null): string
    {
        return $this->scopeConfig->getValue(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    
    /**
     * Is Enable
     *
     * @param string|null|int $storeId
     * @return string|bool
     */
    public function isEnabled($storeId = null): string|bool
    {
        return $this->getConfigFlag(self::XML_PATH_ENABLED, $storeId);
    }
    
    /**
     * Is Debug Enable
     *
     * @param null|string|int $storeId
     * @return string|bool
     */
    public function isDebugEnabled($storeId = null): string|bool
    {
        return $this->getConfigFlag(self::XML_PATH_DEBUG, $storeId);
    }
    
    /**
     * Is Product Link Enable
     *
     * @param null|string|int $storeId
     * @return string|bool
     */
    public function isProductLinkEnabled($storeId = null): string|bool
    {
        return $this->getConfigFlag(self::XML_PATH_ENABLE_PRODUCT_LINK, $storeId);
    }
    
    /**
     * Is Category link enable
     *
     * @param string|null|int $storeId
     * @return string|bool
     */
    public function isCategoryLinkEnabled($storeId = null): string|bool
    {
        return $this->getConfigFlag(self::XML_PATH_ENABLE_CATEGORY_LINK, $storeId);
    }
    
    /**
     * Get customer product url
     *
     * @param string|null|int $storeId
     * @return string|bool
     */
    public function getCustomProductUrlKey($storeId = null): string|bool
    {
        return $this->getConfigValue(self::XML_PATH_CUSTOM_PRODUCT_URL_KEY, $storeId);
    }
}

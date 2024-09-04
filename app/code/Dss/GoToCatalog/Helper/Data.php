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
namespace Dss\GoToCatalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Dss\GoToCatalog\Model\Config;
use Dss\GoToCatalog\Logger\Logger as GoToCatalogLogger;

class Data extends AbstractHelper
{
    /**
     * Data Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Dss\GoToCatalog\Logger\Logger $moduleLogger
     * @param \Dss\GoToCatalog\Model\Config $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        protected GoToCatalogLogger $moduleLogger,
        protected Config $config,
        protected StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
    }
    
    /**
     * Get Config
     *
     * @return \Dss\GoToCatalog\Model\Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
    
    /**
     * Get Url
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route, $params = []): string
    {
        return $this->_getUrl($route, $params);
    }
    
    /**
     * Get Base Url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB, true);
    }
    
    /**
     * Is Active
     *
     * @return string|bool
     */
    public function isActive(): string|bool
    {
        return $this->config->isEnabled();
    }

    /**
     * Logging Utility
     *
     * @param string $message
     * @param bool|false $useSeparator
     */
    public function log($message, $useSeparator = false)
    {
        if ($this->isActive()
            && $this->config->isDebugEnabled()
        ) {
            if ($useSeparator) {
                $this->moduleLogger->customLog(str_repeat('=', 100));
            }

            $this->moduleLogger->customLog($message);
        }
    }
}

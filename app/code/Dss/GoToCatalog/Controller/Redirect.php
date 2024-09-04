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
namespace Dss\GoToCatalog\Controller;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Dss\GoToCatalog\Model\ProductUrlResolver;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NotFoundException;
use Dss\GoToCatalog\Helper\Data as GoToCatalogHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;

abstract class Redirect extends \Magento\Framework\App\Action\Action
{
    /**
     * Enabled config path
     */
    public const XML_PATH_ENABLED = 'dssgotocatalog/general/enabled';

    /**
     * Redirect Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Dss\GoToCatalog\Helper\Data $goToCatalogHelper
     * @param \Dss\GoToCatalog\Model\ProductUrlResolver $productUrlResolver
     */
    public function __construct(
        Context $context,
        protected ScopeConfigInterface $scopeConfig,
        protected StoreManagerInterface $storeManager,
        protected GoToCatalogHelper $goToCatalogHelper,
        protected ProductUrlResolver $productUrlResolver
    ) {
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request):  ResponseInterface
    {
        if (!$this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE)) {
            throw new NotFoundException(__('Page not found.'));
        }
        return parent::dispatch($request);
    }
}

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
namespace Dss\GoToCatalog\Model\Validator;

use Magento\Framework\App\Route\ConfigInterface as RouteConfig;
use Dss\GoToCatalog\Helper\Data as GoToCatalogHelper;

class UrlKey
{
    /**
     * __construct
     *
     * @param \Magento\Framework\App\Route\ConfigInterface $routeConfig
     * @param \Dss\GoToCatalog\Helper\Data $goToCatalogHelper
     */
    public function __construct(
        protected RouteConfig $routeConfig,
        protected GoToCatalogHelper $goToCatalogHelper
    ) {
        $this->routeConfig = $routeConfig;
        $this->goToCatalogHelper = $goToCatalogHelper;
    }

    /**
     * Validate url key
     *
     * @param mixed $urlKey
     * @return bool
     */
    public function validate($urlKey): bool
    {
        $modules = $this->routeConfig->getModulesByFrontName($urlKey, 'frontend');
        return !empty($modules);
    }
}

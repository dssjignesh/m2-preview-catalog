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
namespace Dss\GoToCatalog\Controller\Redirect;

use Dss\GoToCatalog\Controller\Redirect;

class BySku extends \Dss\GoToCatalog\Controller\Redirect
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $sku = $this->getRequest()->getParam('sku');
        if (!strlen($sku)) {
            return $this->performRedirection($this->goToCatalogHelper->getBaseUrl());
        }
        if ($redirectUrl = $this->productUrlResolver->getUrlBySku($sku)) {
            return $this->performRedirection($redirectUrl, 301);
        }

        $this->performRedirection($this->productUrlResolver->getDefaultUrl($sku));
    }
    
    /**
     * Perform redirection
     *
     * @param string $url
     * @param int $code
     */
    private function performRedirection($url, $code = 302)
    {
        $this->getResponse()->setRedirect($url, $code)->sendResponse();
    }
}

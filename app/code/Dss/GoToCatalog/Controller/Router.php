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

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Dss\GoToCatalog\Helper\Data as GoToCatalogHelper;

class Router implements RouterInterface
{
    /**
     * Router Counstructor
     *
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Dss\GoToCatalog\Helper\Data $goToCatalogHelper
     */
    public function __construct(
        protected ActionFactory $actionFactory,
        protected ResponseInterface $response,
        protected ManagerInterface $eventManager,
        protected GoToCatalogHelper $goToCatalogHelper
    ) {
    }

    /**
     * Match
     *
     * @param mixed $request
     * @return void
     */
    public function match(RequestInterface $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');
        if (empty($pathInfo)) {
            return;
        }

        $condition = new DataObject(['identifier' => $pathInfo, 'continue' => true]);
        $this->eventManager->dispatch(
            'dss_gotocatalog_controller_router_match_before',
            ['router' => $this, 'condition' => $condition]
        );

        if ($condition->getRedirectUrl()) {
            $this->response->setRedirect($condition->getRedirectUrl());
            $request->setDispatched(true);
            return $this->actionFactory->create(
                \Magento\Framework\App\Action\Redirect::class,
                ['request' => $request]
            );
        }

        if (!$condition->getContinue()) {
            return null;
        }

        $identifier = $condition->getIdentifier();
        $params = explode('/', $identifier);
        $requestIdentifier = $params[0] ?? '';

        $skuRedirectIdentifier = $this->goToCatalogHelper->getConfig()->getCustomProductUrlKey();
        if ($requestIdentifier == $skuRedirectIdentifier) {
            $sku = $params[1] ?? '';
            $request->setModuleName('dss_gotocatalog')
                ->setControllerName('redirect')
                ->setActionName('bySku')
                ->setParam('sku', $sku);

            return $this->actionFactory->create(
                \Magento\Framework\App\Action\Forward::class,
                ['request' => $request]
            );
        }

        return null;
    }
}

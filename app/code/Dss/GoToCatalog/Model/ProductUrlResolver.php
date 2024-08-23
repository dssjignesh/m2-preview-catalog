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

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;
use Dss\GoToCatalog\Helper\Data as GoToCatalogHelper;
use Dss\GoToCatalog\Model\ParentIdResolver;

class ProductUrlResolver
{
    /**
     * ProductUrlResolver Constructor
     *
     * @param \Dss\GoToCatalog\Helper\Data $goToCatalogHelper
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Dss\GoToCatalog\Model\ParentIdResolver $parentIdResolver
     */
    public function __construct(
        protected GoToCatalogHelper $goToCatalogHelper,
        protected ProductHelper $productHelper,
        protected ProductRepositoryInterface $productRepository,
        protected ParentIdResolver $parentIdResolver
    ) {
    }
    
    /**
     * Get Url by Sku
     *
     * @param string $sku
     * @return bool|string
     */
    public function getUrlBySku($sku): bool|string
    {
        try {
            $product = $this->productRepository->get($sku);
        } catch (\Exception $e) {
            // log exception
            return false;
        }

        if ($product->getTypeId() == ProductType::TYPE_SIMPLE && !$this->productHelper->canShow($product)) {
            if ($parent = $this->getParent($product->getId())) {
                $product = $parent;
            }
        }

        if ($this->productHelper->canShow($product)) {
            return $product->getProductUrl();
        }

        return false;
    }
    
    /**
     * Get Default Url
     *
     * @param string $keyword
     * @return string
     */
    public function getDefaultUrl(string $keyword): string
    {
        return $this->goToCatalogHelper->getUrl('catalogsearch/result', ['_secure' => true]) . '?q=' . $keyword;
    }
    
    /**
     * Get Parent
     *
     * @param int|string $productId
     * @return bool|array|string
     */
    private function getParent($productId): bool|array|string
    {
        $parentProduct = false;
        if ($parentId = $this->parentIdResolver->getParentId($productId, ProductType::TYPE_BUNDLE)) {
            $parentProduct  = $this->productRepository->getById($parentId);
        }

        if (!$parentProduct || ($parentProduct && !$this->productHelper->canShow($parentProduct))) {
            if ($parentId = $this->parentIdResolver->getParentId($productId, ConfigurableType::TYPE_CODE)) {
                $parentProduct = $this->productRepository->getById($parentId);
            }
        }

        if (!$parentProduct || ($parentProduct && !$this->productHelper->canShow($parentProduct))) {
            if ($parentId = $this->parentIdResolver->getParentId($productId, GroupedType::TYPE_CODE)) {
                $parentProduct = $this->productRepository->getById($parentId);
            }
        }

        return $parentProduct;
    }
}

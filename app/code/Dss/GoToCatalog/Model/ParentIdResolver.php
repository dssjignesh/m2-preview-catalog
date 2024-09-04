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

use Magento\Bundle\Model\ResourceModel\Selection as BundleSelection;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableTypeResource;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;

class ParentIdResolver
{
    /**
     * Parent id resolver constructor
     *
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurable
     * @param \Magento\Bundle\Model\ResourceModel\Selection $bundle
     * @param \Magento\GroupedProduct\Model\Product\Type\Grouped $grouped
     */
    public function __construct(
        protected ConfigurableTypeResource $configurable,
        protected BundleSelection $bundle,
        protected GroupedType $grouped
    ) {
    }
    
    /**
     * Get Parent Id
     *
     * @param int $productId
     * @param string $typeId
     * @return string|bool
     */
    public function getParentId($productId, $typeId): string|bool
    {
        $parentIds = [];
        if ($typeId == ConfigurableType::TYPE_CODE) {
            $parentIds = $this->configurable->getParentIdsByChild($productId);
        } elseif ($typeId == ProductType::TYPE_BUNDLE) {
            $parentIds = $this->bundle->getParentIdsByChild($productId);
        } elseif ($typeId == GroupedType::TYPE_CODE) {
            $parentIds = $this->grouped->getParentIdsByChild($productId);
        }
        return $parentIds[0] ?? false;
    }
}

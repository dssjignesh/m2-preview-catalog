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
namespace Dss\GoToCatalog\Test\Unit\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\Product;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Dss\GoToCatalog\Helper\Data as Helper;
use Dss\GoToCatalog\Model\ParentIdResolver;
use Dss\GoToCatalog\Model\ProductUrlResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Bundle\Model\ResourceModel\Selection as BundleType;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableType;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;

class ProductUrlResolverTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var ProductUrlResolver
     */
    protected $model;
    
    /**
     * Mock Helper
     *
     * @var mixed
     */
    protected $helperMock;

    /**
     * @var StoreManagerInterface|MockObject
     */
    protected $storeManagerMock;

    /**
     * @var Store|MockObject
     */
    protected $storeMock;
    
    /**
     * Mock Product
     *
     * @var mixed
     */
    protected $productMock;
    
    /**
     * Mock product repository
     *
     * @var mixed
     */
    protected $productRepositoryMock;
    
    /**
     * Mock Product helper
     *
     * @var mixed
     */
    protected $productHelperMock;
    
    /**
     * Mock parent id resolver
     *
     * @var mixed
     */
    protected $parentIdResolverMock;
    
    /**
     * Mock bundle type Mock
     *
     * @var mixed
     */
    protected $bundleTypeMock;

    /**
     * Mock configurable type
     *
     * @var mixed
     */
    protected $configurableTypeMock;

    /**
     * Mock group type
     *
     * @var mixed
     */
    protected $groupedTypeMock;

    public function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->productMock = $this->createPartialMock(
            Product::class,
            ['getId', 'getTypeId', 'getProductUrl']
        );

        $this->productRepositoryMock = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->setMethods(['getById', 'get'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->productHelperMock = $this->createPartialMock(
            ProductHelper::class,
            ['canShow']
        );

        $this->helperMock = $this->createPartialMock(
            Helper::class,
            ['getUrl', 'getBaseUrl']
        );

        $this->bundleTypeMock = $this->createPartialMock(
            BundleType::class,
            ['getParentIdsByChild']
        );
        $this->configurableTypeMock = $this->createPartialMock(
            ConfigurableType::class,
            ['getParentIdsByChild']
        );
        $this->groupedTypeMock = $this->createPartialMock(
            GroupedType::class,
            ['getParentIdsByChild']
        );
        $this->parentIdResolverMock = $this->createPartialMock(
            ParentIdResolver::class,
            ['getParentId']
        );

        $this->model = $this->objectManager->getObject(
            ProductUrlResolver::class,
            [
                'goToCatalogHelper' => $this->helperMock,
                'productHelper' => $this->productHelperMock,
                'productRepository' => $this->productRepositoryMock,
                'parentIdResolver' => $this->parentIdResolverMock
            ]
        );
    }

    /**
     * @dataProvider defaultUrlDataProvider
     */
    public function testDefaultUrl($keyword, $url)
    {
        $this->helperMock->expects(
            $this->any()
        )->method(
            'getUrl'
        )->with(
            'catalogsearch/result'
        )->willReturnCallback(
            function ($arg) {
                return 'http://localhost/' . $arg;
            }
        );

        $this->assertEquals($url, $this->model->getDefaultUrl($keyword));
    }

    public function defaultUrlDataProvider(): array
    {
        return [
            ['sku1', 'http://localhost/catalogsearch/result?q=sku1'],
            ['sku2', 'http://localhost/catalogsearch/result?q=sku2'],
        ];
    }

    /**
     * @dataProvider urlBySkuDataProvider
     */
    public function testGetUrlBySku($sku, $productUrl, $typeId, $canView)
    {
        $this->productMock->expects(
            $this->any()
        )->method(
            'getTypeId'
        )->willReturn(
            $typeId
        );
        $this->productMock->expects(
            $this->any()
        )->method(
            'getId'
        )->willReturn(
            1
        );
        $this->productMock->expects(
            $this->any()
        )->method(
            'getProductUrl'
        )->willReturn(
            'http://localhost/product/' . $sku . '.html'
        );

        $this->productRepositoryMock->expects(
            $this->any()
        )->method(
            'get'
        )->with(
            $sku
        )->willReturn(
            $this->productMock
        );

        $this->productRepositoryMock->expects(
            $this->any()
        )->method(
            'getById'
        )->willReturn(
            $this->productMock
        );

        $this->productHelperMock->expects(
            $this->any()
        )->method(
            'canShow'
        )->with(
            $this->productMock
        )->willReturn(
            $canView
        );

        $this->parentIdResolverMock->expects($this->any())
            ->method('getParentId')
            ->willReturn(
                1
            );

        $this->assertEquals($productUrl, $this->model->getUrlBySku($sku));
    }
    
    /**
     * urlBySkuDataProvider
     *
     * @return array
     */
    public function urlBySkuDataProvider(): array
    {
        return [
            'simple-visible' => ['sku1', 'http://localhost/product/sku1.html', 'simple', 1],
            //'simple-not-visible' => ['sku2', 'http://localhost/product/sku2.html', 'simple', 0],
            'bundle-visible' => ['sku3', 'http://localhost/product/sku3.html', 'bundle', 1],
            //'bundle-not-visible' => ['sku4', 'http://localhost/product/sku4.html', 'bundle', 0],
            'configurable-visible' => ['sku5', 'http://localhost/product/sku5.html', 'configurable', 1],
            //'configurable-not-visible' => ['sku6', 'http://localhost/product/sku6.html', 'configurable', 0],
            'grouped-visible' => ['sku7', 'http://localhost/product/sku7.html', 'grouped', 1],
            //'grouped-not-visible' => ['sku8', 'http://localhost/product/sku8.html', 'grouped', 0],
        ];
    }
}

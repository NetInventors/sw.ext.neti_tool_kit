<?php
/**
 * @copyright  Copyright (c) 2017, Net Inventors GmbH
 * @category   Shopware
 * @author     rubyc
 */

namespace NetiToolKit\Service\Decorations;

use NetiFoundation\Service\PluginManager\Config;
use NetiToolKit\Struct\PluginConfig;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\PropertyServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\SimilarProductsServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Bundle\StoreFrontBundle\Struct\Product;
use Shopware\Bundle\StoreFrontBundle\Struct\ProductContextInterface;

/**
 * @category  Shopware
 * @package   Shopware\Bundle\StoreFrontBundle\Service\Core
 * @copyright Copyright (c) shopware AG (http://www.shopware.de)
 */
class SimilarProductsService implements SimilarProductsServiceInterface
{
    /**
     * @var SimilarProductsServiceInterface
     */
    private $coreService;

    /**
     * @var PluginConfig $config
     */
    private $pluginConfig;

    /**
     * @var PropertyServiceInterface
     */
    private $propertyService;

    /**
     * @var ContextServiceInterface
     */
    private $contextService;

    /**
     * SimilarProductsService constructor.
     *
     * @param SimilarProductsServiceInterface $coreService
     * @param Config                          $configService
     * @param PropertyServiceInterface        $propertyService
     * @param ContextServiceInterface         $contextService
     */
    public function __construct(
      SimilarProductsServiceInterface $coreService,
      Config $configService,
      PropertyServiceInterface $propertyService,
      ContextServiceInterface $contextService
    ) {
        $this->coreService     = $coreService;
        $this->pluginConfig    = $configService->getPluginConfig($this);
        $this->propertyService = $propertyService;
        $this->contextService  = $contextService;
    }

    /**
     * @inheritdoc
     */
    public function get(ListProduct $product, ProductContextInterface $context)
    {
        return $this->getList($product, $context);;
    }

    /**
     * @inheritdoc
     */
    public function getList($products, ProductContextInterface $context)
    {
        $similarProducts   = $this->coreService->getList($products, $context);

        if ($this->pluginConfig->isListingProperties()) {
            $mainArticleNumber = key($similarProducts);
            $similarProducts   = array_shift($similarProducts);
            // get property set Structs
            $propertySets  = $this->propertyService->getList($similarProducts, $this->contextService->getShopContext());
            $productswthProperties = [];
            foreach ($similarProducts as $similarProduct) {
                $number         = $similarProduct->getNumber();
                /** @var Product $similarProduct */
                $similarProduct = Product::createFromListProduct($similarProduct);
                $propertySet    = $propertySets[$similarProduct->getNumber()];
                $similarProduct->setPropertySet($propertySet);
                $productswthProperties[$number] = $similarProduct;
            }
            $products[$mainArticleNumber] = $productswthProperties;

            return $products;
        } else {
            return $similarProducts;
        }
    }
}

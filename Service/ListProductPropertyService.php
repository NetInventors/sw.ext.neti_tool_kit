<?php
/**
 * Created by PhpStorm.
 * User: hrombach
 * Date: 6/14/16
 * Time: 12:51 PM
 */

namespace Shopware\NetiArticleListingProperties\Service;

use Doctrine\DBAL\Driver\Connection;
use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\PropertyServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class ListProductPropertyService implements ListProductServiceInterface
{
    /** @var Connection */
    private $connection;

    /** @var ListProductServiceInterface */
    private $coreService;

    /** @var PropertyServiceInterface */
    private $propertyService;

    /**
     * ProductPropertyService constructor.
     *
     * @param ListProductServiceInterface $service
     * @param Connection                  $connection
     * @param PropertyServiceInterface    $propertyService
     */
    public function __construct(
        ListProductServiceInterface $service,
        Connection $connection,
        PropertyServiceInterface $propertyService
    )
    {
        $this->connection      = $connection;
        $this->coreService     = $service;
        $this->propertyService = $propertyService;
    }

    /**
     * To get detailed information about the selection conditions, structure and content of the returned object,
     * please refer to the linked classes.
     *
     * @see \Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface::get()
     *
     * @param array                          $numbers
     * @param Struct\ProductContextInterface $context
     *
     * @return Struct\ListProduct[] Indexed by the product order number.
     */
    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $products     = $this->coreService->getList($numbers, $context);
        $propertySets = $this->propertyService->getList($products, $context);

        foreach ($products as $product) {
            $product->addAttribute(
                'netiPropertySets',
                new Struct\Attribute(array($propertySets[ $product->getNumber() ]))
            );
        }
        
        return $products;
    }

    /**
     * Returns a full \Shopware\Bundle\StoreFrontBundle\Struct\ListProduct object.
     * A list product contains all required data to display products in small views like listings, sliders or emotions.
     *
     * @param string                         $number
     * @param Struct\ProductContextInterface $context
     *
     * @return Struct\ListProduct
     */
    public function get($number, Struct\ProductContextInterface $context)
    {
        return $this->coreService->get($number, $context);
    }
}

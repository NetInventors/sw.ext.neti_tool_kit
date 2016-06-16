<?php
/**
 * Created by PhpStorm.
 * User: hrombach
 * Date: 6/15/16
 * Time: 3:45 PM
 */

namespace Shopware\NetiToolKit;

use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Bundle\StoreFrontBundle\Struct\Property\Set;

class PropertyListProduct extends ListProduct
{
    /**
     * @var Set
     */
    protected $propertySet;

    /**
     * @param ListProduct $listProduct
     *
     * @return PropertyListProduct
     */
    public static function createFromListProduct(ListProduct $listProduct)
    {
        $product = new self(
            $listProduct->getId(),
            $listProduct->getVariantId(),
            $listProduct->getNumber()
        );
        foreach ($listProduct as $key => $value) {
            $product->{$key} = $value;
        }

        return $product;
    }

    /**
     * @return Set
     */
    public function getPropertySet()
    {
        return $this->propertySet;
    }

    /**
     * @param Set $propertySet
     *
     * @return PropertyListProduct
     */
    public function setPropertySet($propertySet)
    {
        $this->propertySet = $propertySet;

        return $this;
    }
}

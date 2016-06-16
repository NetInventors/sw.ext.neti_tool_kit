<?php
/**
 * Created by PhpStorm.
 * User: hrombach
 * Date: 6/15/16
 * Time: 5:11 PM
 */

namespace Shopware\NetiToolKit\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\BaseProduct;

class ListingProperties implements SubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (position defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     * <code>
     * return array(
     *     'eventName0' => 'callback0',
     *     'eventName1' => array('callback1'),
     *     'eventName2' => array('callback2', 10),
     *     'eventName3' => array(
     *         array('callback3_0', 5),
     *         array('callback3_1'),
     *         array('callback3_2')
     *     )
     * );
     *
     * </code>
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'sArticles::sGetArticlesByCategory::after' => 'afterGetArticlesByCategory'
        ];
    }

    public function afterGetArticlesByCategory(\Enlight_Hook_HookArgs $args)
    {
        $return          = $args->getReturn();
        $container       = Shopware()->Container();
        $context         = $container->get('shopware_storefront.context_service')->getContext();
        $propertyService = $container->get('shopware_storefront.property_service');
        $structConverter = $container->get('legacy_struct_converter');

        //turn sArticles array into BaseProduct Structs
        $products = [];
        foreach ($return['sArticles'] as $sArticle) {
            $products[ $sArticle['ordernumber'] ] = new BaseProduct(
                $sArticle['articleID'],
                $sArticle['articleDetailsID'],
                $sArticle['ordernumber']
            );
        }

        // get property set Structs
        $propertySets = $propertyService->getList($products, $context);

        // convert property set Structs to legacy Array format
        $legacyProps = [];
        foreach ($propertySets as $ordernumber => $propertySet) {
            $legacyProps[ $ordernumber ] = $structConverter->convertPropertySetStruct($propertySet);
        }

        // add property arrays to sArticles array
        foreach ($return['sArticles'] as &$sArticle) {
            $sArticle['sProperties'] = $legacyProps[ $sArticle['ordernumber'] ];
        }

        $args->setReturn($return);
    }
}

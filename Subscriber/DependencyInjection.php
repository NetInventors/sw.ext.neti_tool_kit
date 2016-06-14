<?php
/**
 * Created by PhpStorm.
 * User: hrombach
 * Date: 6/14/16
 * Time: 1:22 PM
 */

namespace Shopware\NetiArticleListingProperties\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\NetiArticleListingProperties\Service\ListProductPropertyService;

class DependencyInjection implements SubscriberInterface
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
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service' => 'decorateProductListService'
        ];
    }

    public function decorateProductListService(\Enlight_Event_EventArgs $args)
    {
        $dic = Shopware()->Container();
        
        $listingPropertyService = new ListProductPropertyService(
            $dic->get('shopware_storefront.list_product_service'),
            $dic->get('dbal_connection'),
            $dic->get('shopware_storefront.property_service')
        );

        $dic->set('shopware_storefront.list_product_service', $listingPropertyService);
    }
}

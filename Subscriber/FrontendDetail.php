<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
 */

namespace NetiToolKit\Subscriber;

use Enlight\Event\SubscriberInterface;

class FrontendDetail implements SubscriberInterface
{
    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return ['Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'onPostDispatchSecureFrontendDetail'];
    }

    public function onPostDispatchSecureFrontendDetail(\Enlight_Controller_ActionEventArgs $args)
    {
        $view     = $args->getSubject()->View();
        $sArticle = $view->getAssign('sArticle');

        foreach ($sArticle['sProperties'] as $propertyKey => &$property) {
            if (isset($property['attributes']['core']) &&
                '1' === $property['attributes']['core']->get('neti_tool_kit_hide_in_frontend')) {
                unset($sArticle['sProperties'][$propertyKey]);
                continue;
            }

            foreach ($property['options'] as $optionKey => $option) {
                if (isset($option['attributes']['core']) &&
                    '1' === $option['attributes']['core']->get('neti_tool_kit_hide_in_frontend')) {
                    $property['value'] = trim(str_replace($option['name'], '', $property['value']), ', ');
                    unset($property['values'][$option['id']], $sArticle['sProperties']['options'][$optionKey]);
                }
            }
        }
        unset($property);

        $view->assign('sArticle', $sArticle);
    }
}

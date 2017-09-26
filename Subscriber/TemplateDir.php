<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
 */

namespace NetiToolKit\Subscriber;

use Enlight\Event\SubscriberInterface;

class TemplateDir implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDir;

    /**
     * TemplateDir constructor.
     *
     * @param string $pluginDir
     */
    public function __construct($pluginDir)
    {
        $this->pluginDir = $pluginDir;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'onPostDispatchSecureFrontendDetail',
        ];
    }

    public function onPostDispatchSecureFrontendDetail(\Enlight_Controller_ActionEventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir($this->pluginDir . '/Resources/views');
    }
}

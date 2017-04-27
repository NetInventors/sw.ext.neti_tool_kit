<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
 */

namespace NetiToolKit\Subscriber;

use Enlight\Event\SubscriberInterface;
use NetiFoundation\Service\PluginManager\Config;
use NetiToolKit\Struct\PluginConfig;
use Shopware\Components\Model\ModelManager;

class GlobalData implements SubscriberInterface
{
    /** @var bool */
    private $userLoggedIn;

    /** @var Config */
    private $configService;

    /**
     * @var PluginConfig
     */
    private $pluginConfig;

    /**
     * @var \Enlight_Components_Session_Namespace
     */
    private $session;

    /**
     * @var ModelManager
     */
    private $em;

    /**
     * GlobalData constructor.
     *
     * @param Config                                $configService
     * @param \Enlight_Components_Session_Namespace $session
     * @param ModelManager                          $em
     */
    public function __construct(Config $configService, \Enlight_Components_Session_Namespace $session, ModelManager $em)
    {
        $this->configService = $configService;
        $this->session       = $session;
        $this->pluginConfig  = $configService->getPluginConfig('NetiToolKit');
        $this->em            = $em;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend' => 'addSmartyGlobals',
            'Enlight_Controller_Action_PostDispatch_Widgets'  => 'addSmartyGlobals',
        ];
    }

    /**
     * Assigns global smarty variables
     *
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function addSmartyGlobals(\Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();

        if (null === $this->userLoggedIn) {
            $this->userLoggedIn = (bool)$this->session->sUserId;
        }

        // assign customer login state to smarty
        if ($this->pluginConfig->isGlobalLoginState()) {
            if ($view->hasTemplate()) {
                $view->assign('sUserLoggedIn', $this->userLoggedIn);
            }
        }

        $netiUserData = [];

        // assign userData array to smarty
        if ($this->pluginConfig->isGlobalUserData() && $this->userLoggedIn) {
            $userData     = Shopware()->Modules()->Admin()->sGetUserData();
            $netiUserData = [
                'sUserID'                           => $userData['additional']['user']['id'],
                'sUserCompany'                      => $userData['additional']['user']['company'],
                'sUserEmail'                        => $userData['additional']['user']['email'],
                'sUserAccountmode'                  => $userData['additional']['user']['accountmode'],
                'sUserPaymentID'                    => $userData['additional']['user']['paymentID'],
                'sUserFirstlogin'                   => $userData['additional']['user']['firstlogin'],
                'sUserLastlogin'                    => $userData['additional']['user']['lastlogin'],
                'sUserNewsletter'                   => (bool)$userData['additional']['user']['newsletter'],
                'sUserAffiliate'                    => (bool)$userData['additional']['user']['affiliate'],
                'sUserCustomergroup'                => $userData['additional']['user']['customergroup'],
                'sUserPaymentpreset'                => $userData['additional']['user']['paymentpreset'],
                'sUserLanguage'                     => $userData['additional']['user']['language'],
                'sUserSubshopID'                    => $userData['additional']['user']['subshopID'],
                'sUserPricegroupID'                 => $userData['additional']['user']['pricegroupID'],
                'sUserInternalcomment'              => $userData['additional']['user']['internalcomment'],
                'sUserBillingaddressSalutation'     => $userData['billingaddress']['salutation'],
                'sUserBillingaddressFirstname'      => $userData['billingaddress']['firstname'],
                'sUserBillingaddressLastname'       => $userData['billingaddress']['lastname'],
                'sUserBillingaddressCustomernumber' => $userData['billingaddress']['customernumber'],
                'sUserBillingaddressStreet'         => $userData['billingaddress']['street'],
                'sUserBillingaddressZipcode'        => $userData['billingaddress']['zipcode'],
                'sUserBillingaddressCity'           => $userData['billingaddress']['city'],
                'sUserBillingaddressPhone'          => $userData['billingaddress']['phone'],
                'sUserBillingaddressFax'            => $userData['billingaddress']['fax'],
                'sUserBillingaddressCountryID'      => $userData['billingaddress']['countryID'],
                'sUserBillingaddressStateID'        => $userData['billingaddress']['stateID'],
                'sUserBillingaddressBirthday'       => $userData['billingaddress']['birthday'],
            ];
        }

        if ($this->pluginConfig->getGlobalUserAttributeData() && $this->userLoggedIn)
        {
            $userId       = $this->session->offsetGet('sUserId');
            $customerData = $this->em
              ->getRepository('Shopware\Models\Customer\Customer')
              ->findOneBy(array('id' => $userId));
            foreach ($this->pluginConfig->getGlobalUserAttributeData() as $item) {
                if ($item === 's_user_addresses_attributes') {
                    $userAddressAttributes                  = (array)$customerData->getDefaultBillingAddress()->getAttribute();
                    $netiUserData['sUserAddressAttributes'] = $userAddressAttributes;
                }
                if ($item === 's_user_billingaddress_attributes') {
                    $userBillingAttributes                         = (array)$customerData->getBilling()->getAttribute();
                    $netiUserData['sUserBillingaddressAttributes'] = $userBillingAttributes;
                }
                if ($item === 's_user_shippingaddress_attributes') {
                    $userShippingAttributes                         = (array)$customerData->getShipping()->getAttribute();
                    $netiUserData['sUserShippingaddressAttributes'] = $userShippingAttributes;
                }
            }
        }

        $view->assign('netiUserData', $netiUserData);

    }
}

<?php
/**
 * Created by PhpStorm.
 * User: hrombach
 * Date: 6/16/16
 * Time: 12:33 PM
 */

namespace NetiToolKit\Subscriber;

use Enlight\Event\SubscriberInterface;
use NetiFoundation\Service\PluginManager\Config;
use NetiToolKit\Struct\PluginConfig;

class GlobalData implements SubscriberInterface
{
    /** @var  bool */
    private $userLoggedIn;

    /** @var  Config */
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
     * GlobalData constructor.
     *
     * @param Config                                $configService
     * @param \Enlight_Components_Session_Namespace $session
     */
    public function __construct(Config $configService, \Enlight_Components_Session_Namespace $session)
    {
        $this->configService = $configService;
        $this->session       = $session;
        $this->pluginConfig  = $configService->getPluginConfig('NetiToolKit');
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

        if ($this->pluginConfig->isGlobalLoginState()) {
            if ($view->hasTemplate()) {
                $view->assign('sUserLoggedIn', $this->userLoggedIn);
            }
        }

        if ($this->pluginConfig->isGlobalUserData() && $this->userLoggedIn) {
            $userData     = Shopware()->Modules()->Admin()->sGetUserData();
            $netiUserData = array(
                'sUserID'                           => $userData['additional']['user']['id'],
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
            );
            $view->assign('netiUserData', $netiUserData);
        }
    }
}

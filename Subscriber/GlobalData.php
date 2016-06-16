<?php
/**
 * Created by PhpStorm.
 * User: hrombach
 * Date: 6/16/16
 * Time: 12:33 PM
 */

namespace Shopware\NetiToolKit\Subscriber;

use Enlight\Event\SubscriberInterface;

class GlobalData implements SubscriberInterface
{
    /** @var  bool */
    private $userLoggedIn;

    /** @var  \Enlight_Config */
    private $pluginConfig;

    /**
     * GlobalData constructor.
     *
     * @param \Enlight_Config $pluginConfig
     */
    public function __construct(\Enlight_Config $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

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
            $this->userLoggedIn = (bool) Shopware()->Session()->offsetGet('sUserId');
        }

        if ($this->pluginConfig['globalLoginState']) {
            if ($view->hasTemplate()) {
                $view->assign('sUserLoggedIn', $this->userLoggedIn);
            }
        }

        var_dump($this->pluginConfig->toArray(), $this->userLoggedIn);

        if ($this->pluginConfig['globalUserData'] && $this->userLoggedIn) {
            $userData     = Shopware()->Modules()->Admin()->sGetUserData();
            $netiUserData = array(
                'sUserID'                           => $userData['additional']['user']['id'],
                'sUserEmail'                        => $userData['additional']['user']['email'],
                'sUserAccountmode'                  => $userData['additional']['user']['accountmode'],
                'sUserPaymentID'                    => $userData['additional']['user']['paymentID'],
                'sUserFirstlogin'                   => $userData['additional']['user']['firstlogin'],
                'sUserLastlogin'                    => $userData['additional']['user']['lastlogin'],
                'sUserNewsletter'                   => (bool) $userData['additional']['user']['newsletter'],
                'sUserAffiliate'                    => (bool) $userData['additional']['user']['affiliate'],
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

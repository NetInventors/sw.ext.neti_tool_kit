<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
 */

namespace NetiToolKit\Subscriber;

use Doctrine\ORM\AbstractQuery;
use Enlight\Event\SubscriberInterface;
use NetiFoundation\Service\PluginManager\ConfigInterface;
use NetiToolKit\Struct\PluginConfig;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Customer\Customer;

class GlobalData implements SubscriberInterface
{
    /**
     * @var bool
     */
    private $userLoggedIn;

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
     * @param ConfigInterface                       $configService
     * @param \Enlight_Components_Session_Namespace $session
     * @param ModelManager                          $em
     */
    public function __construct(
        ConfigInterface $configService,
        \Enlight_Components_Session_Namespace $session,
        ModelManager $em
    ) {
        $this->session      = $session;
        $this->em           = $em;
        $this->pluginConfig = $configService->getPluginConfig('NetiToolKit');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'addSmartyGlobals',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets'  => 'addSmartyGlobals',
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

        $netiUserData = [];

        // assign userData array to smarty
        if ($this->pluginConfig->isGlobalUserData() && $this->userLoggedIn) {
            $this->addUserData($netiUserData);
        }

        if ($this->pluginConfig->isGlobalUserAttributeData() && $this->userLoggedIn) {
            $this->addUserAttributes($netiUserData);
        }

        $view->assign('sUserLoggedIn', $this->userLoggedIn);
        $view->assign('netiUserData', $netiUserData);
    }

    /**
     * @param $netiUserData
     *
     * @return void
     */
    private function addUserData(&$netiUserData)
    {
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

    /**
     * @param $netiUserData
     *
     * @return void
     */
    private function addUserAttributes(&$netiUserData)
    {
        $userId = $this->session->offsetGet('sUserId');
        $qb     = $this->em->createQueryBuilder();
        $qb->from(Customer::class, 'c')
           ->select(['c', 'ca', 'cdb', 'cdba', 'cb', 'cba', 'cs', 'csa'])
           ->leftJoin('c.attribute', 'ca')
           ->leftJoin('c.defaultBillingAddress', 'cdb')
           ->leftJoin('cdb.attribute', 'cdba')
           ->leftJoin('c.billing', 'cb')
           ->leftJoin('cb.attribute', 'cba')
           ->leftJoin('c.shipping', 'cs')
           ->leftJoin('cs.attribute', 'csa')
           ->where($qb->expr()->eq('c.id', ':customerId'))
           ->setMaxResults(1);

        $attributeData = array_shift($qb->getQuery()->execute(['customerId' => $userId], AbstractQuery::HYDRATE_ARRAY));

        $netiUserData += [
            'sUserAttribute'         => $attributeData['attribute'],
            'sUserShippingAttribute' => $attributeData['shipping']['attribute'],
            'sUserBillingAttribute'  => $attributeData['billing']['attribute'],
            'sUserAddressAttribute'  => $attributeData['defaultBillingAddress']['attribute'],
        ];
    }
}

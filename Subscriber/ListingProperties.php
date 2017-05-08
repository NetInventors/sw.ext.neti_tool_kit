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
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\PropertyServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\BaseProduct;
use Shopware\Components\Compatibility\LegacyStructConverter;

class ListingProperties implements SubscriberInterface
{
    /**
     * @var ContextServiceInterface
     */
    private $contextService;

    /**
     * @var PropertyServiceInterface
     */
    private $propertyService;

    /**
     * @var LegacyStructConverter
     */
    private $structConverter;

    /**
     * @var Config
     */
    private $configService;

    /**
     * @var PluginConfig
     */
    private $pluginConfig;

    /**
     * @var \Shopware_Components_Modules
     */
    private $modules;

    /**
     * ListingProperties constructor.
     *
     * @param ContextServiceInterface      $contextService
     * @param PropertyServiceInterface     $propertyService
     * @param LegacyStructConverter        $structConverter
     * @param Config                       $configService
     * @param \Shopware_Components_Modules $modules
     */
    public function __construct(
        ContextServiceInterface $contextService,
        PropertyServiceInterface $propertyService,
        LegacyStructConverter $structConverter,
        Config $configService,
        \Shopware_Components_Modules $modules
    ) {
        $this->contextService  = $contextService;
        $this->propertyService = $propertyService;
        $this->structConverter = $structConverter;
        $this->configService   = $configService;
        $this->pluginConfig    = $configService->getPluginConfig('NetiToolKit');
        $this->modules         = $modules;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
          'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail'     => 'onPostDispatchFrontendDetail',
          'sArticles::sGetArticlesByCategory::after'                         => 'afterGetArticlesByCategory',
          'Shopware_Controllers_Widgets_Listing::topSellerAction::after'     => 'afterTopSellerAction',
          'Shopware_Controllers_Widgets_Recommendation::boughtAction::after' => 'afterBoughtAction'
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchFrontendDetail(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Detail $subject */
        $view      = $args->getSubject()->View();
        $sArticles = $view->sArticle;

        // add property arrays to sArticles array
        foreach ($sArticles['sSimilarArticles'] as $key => $similarArticle) {
            $sArticles['sSimilarArticles'][$key]['sProperties'] =  $this->modules->Articles()->sGetArticleProperties($similarArticle['articleID']);
        }

        $view->sArticle = $sArticles;
    }


    public function afterTopSellerAction(\Enlight_Hook_HookArgs $args)
    {
        if (!$this->pluginConfig->isListingProperties()) {
            return;
        }

        $view     = $args->getSubject()->View();
        $sCharts  = $view->sCharts;
        $products = $this->getProductStructsFromViewArticles($sCharts);

        // get property set Structs
        $propertySets = $this->propertyService->getList($products, $this->contextService->getShopContext());
        $legacyProps  = $this->convertPropertyStructs($propertySets);

        // add property arrays to sArticles array
        foreach ($sCharts as &$sArticle) {
            $sArticle['sProperties'] = $legacyProps[$sArticle['ordernumber']];
        }
        unset($sArticle);

        $args->getSubject()->View()->sCharts = $sCharts;
    }

    public function afterBoughtAction(\Enlight_Hook_HookArgs $args)
    {
        if (!$this->pluginConfig->isListingProperties()) {
            return;
        }

        $view           = $args->getSubject()->View();
        $boughtArticles = $view->boughtArticles;
        $products       = $this->getProductStructsFromViewArticles($boughtArticles);

        // get property set Structs
        $propertySets = $this->propertyService->getList($products, $this->contextService->getShopContext());
        $legacyProps  = $this->convertPropertyStructs($propertySets);

        // add property arrays to sArticles array
        foreach ($boughtArticles as &$sArticle) {
            $sArticle['sProperties'] = $legacyProps[$sArticle['ordernumber']];
        }

        unset($sArticle);

        $args->getSubject()->View()->boughtArticles = $boughtArticles;
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function afterGetArticlesByCategory(\Enlight_Hook_HookArgs $args)
    {
        if (!$this->pluginConfig->isListingProperties()) {
            return;
        }

        $return   = $args->getReturn();
        $products = $this->getProductStructsFromViewArticles($return['sArticles']);

        // get property set Structs
        $propertySets = $this->propertyService->getList($products, $this->contextService->getShopContext());
        $legacyProps  = $this->convertPropertyStructs($propertySets);

        // add property arrays to sArticles array
        foreach ($return['sArticles'] as &$sArticle) {
            $sArticle['sProperties'] = $legacyProps[$sArticle['ordernumber']];
        }

        unset($sArticle);

        $args->setReturn($return);
    }

    /**
     * @param array $sArticles
     *
     * @return BaseProduct[]
     */
    private function getProductStructsFromViewArticles(array $sArticles)
    {
        //turn sArticles array into BaseProduct Structs
        $products = [];
        foreach ($sArticles as $sArticle) {
            $products[$sArticle['ordernumber']] = new BaseProduct(
                $sArticle['articleID'],
                $sArticle['articleDetailsID'],
                $sArticle['ordernumber']
            );
        }

        return $products;
    }

    /**
     * @param $propertySets
     *
     * @return array
     */
    private function convertPropertyStructs($propertySets)
    {
        // convert property set Structs to legacy Array format
        $legacyProps = [];
        foreach ($propertySets as $ordernumber => $propertySet) {
            $legacyProps[$ordernumber] = $this->structConverter->convertPropertySetStruct($propertySet);
        }

        return $legacyProps;
    }
}

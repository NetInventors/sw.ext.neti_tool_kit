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
     * ListingProperties constructor.
     *
     * @param ContextServiceInterface  $contextService
     * @param PropertyServiceInterface $propertyService
     * @param LegacyStructConverter    $structConverter
     * @param Config                   $configService
     */
    public function __construct(
        ContextServiceInterface $contextService,
        PropertyServiceInterface $propertyService,
        LegacyStructConverter $structConverter,
        Config $configService
    ) {
        $this->contextService  = $contextService;
        $this->propertyService = $propertyService;
        $this->structConverter = $structConverter;
        $this->configService   = $configService;
        $this->pluginConfig    = $configService->getPluginConfig('NetiToolKit');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'sArticles::sGetArticlesByCategory::after' => 'afterGetArticlesByCategory',
        ];
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function afterGetArticlesByCategory(\Enlight_Hook_HookArgs $args)
    {
        if (!$this->pluginConfig->isListingProperties()) {
            return;
        }

        $return = $args->getReturn();

        //turn sArticles array into BaseProduct Structs
        $products = [];
        foreach ($return['sArticles'] as $sArticle) {
            $products[$sArticle['ordernumber']] = new BaseProduct(
                $sArticle['articleID'],
                $sArticle['articleDetailsID'],
                $sArticle['ordernumber']
            );
        }

        // get property set Structs
        $propertySets = $this->propertyService->getList($products, $this->contextService->getContext());

        // convert property set Structs to legacy Array format
        $legacyProps = [];
        foreach ($propertySets as $ordernumber => $propertySet) {
            $legacyProps[$ordernumber] = $this->structConverter->convertPropertySetStruct($propertySet);
        }

        // add property arrays to sArticles array
        foreach ($return['sArticles'] as &$sArticle) {
            $sArticle['sProperties'] = $legacyProps[$sArticle['ordernumber']];
        }
        unset($sArticle);

        $args->setReturn($return);
    }
}

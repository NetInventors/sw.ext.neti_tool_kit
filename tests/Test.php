<?php

class PluginTest extends Shopware\Components\Test\Plugin\TestCase
{
    protected static $ensureLoadedPlugins = array(
        'NetiArticleListingProperties' => array(
        )
    );

    public function setUp()
    {
        parent::setUp();

        $helper = \TestHelper::Instance();
        $loader = $helper->Loader();


        $pluginDir = getcwd() . '/../';

        $loader->registerNamespace(
            'Shopware\\NetiArticleListingProperties',
            $pluginDir
        );
    }

    public function testCanCreateInstance()
    {
        /** @var Shopware_Plugins_Frontend_NetiArticleListingProperties_Bootstrap $plugin */
        $plugin = Shopware()->Plugins()->Frontend()->NetiArticleListingProperties();

        $this->assertInstanceOf('Shopware_Plugins_Frontend_NetiArticleListingProperties_Bootstrap', $plugin);
    }
}

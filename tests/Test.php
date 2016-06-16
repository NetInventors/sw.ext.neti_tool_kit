<?php

class PluginTest extends Shopware\Components\Test\Plugin\TestCase
{
    protected static $ensureLoadedPlugins = array(
        'NetiToolKit' => array(
        )
    );

    public function setUp()
    {
        parent::setUp();

        $helper = \TestHelper::Instance();
        $loader = $helper->Loader();


        $pluginDir = getcwd() . '/../';

        $loader->registerNamespace(
            'Shopware\\NetiToolKit',
            $pluginDir
        );
    }

    public function testCanCreateInstance()
    {
        /** @var Shopware_Plugins_Frontend_NetiToolKit_Bootstrap $plugin */
        $plugin = Shopware()->Plugins()->Frontend()->NetiToolKit();

        $this->assertInstanceOf('Shopware_Plugins_Frontend_NetiToolKit_Bootstrap', $plugin);
    }
}

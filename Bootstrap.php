<?php
use NetiFoundation\Component\Neti;
use Shopware\NetiToolKit\Subscriber;

/**
 * The Bootstrap class is the main entry point of any shopware plugin.
 *
 * Short function reference
 * - install: Called a single time during (re)installation. Here you can trigger install-time actions like
 *   - creating the menu
 *   - creating attributes
 *   - creating database tables
 *   You need to return "true" or array('success' => true, 'invalidateCache' => array()) in order to let the
 *   installation be successfull
 *
 * - update: Triggered when the user updates the plugin. You will get passes the former version of the plugin as param
 *   In order to let the update be successful, return "true"
 *
 * - uninstall: Triggered when the plugin is reinstalled or uninstalled. Clean up your tables here.
 */
class Shopware_Plugins_Frontend_NetiToolKit_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Foundation
     **/
    protected static $moduleName         = 'NetiToolKit';

    protected static $redmine            = array('000000-012-389', 'hr@netinventors.de');

    protected static $requiredFoundation = '1.9.9';

    protected        $neti;

    public function getVersion()
    {
        $info = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'plugin.json'), true);
        if ($info) {
            return $info['currentVersion'];
        } else {
            throw new Exception('The plugin has an invalid version file.');
        }
    }

    public function getLabel()
    {
        $info = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'plugin.json'), true);
        if ($info) {
            return $info['label']['en'];
        } else {
            throw new Exception('The plugin has an invalid version file.');
        }
    }

    /**
     * Returns the plugin information
     *
     * @return array plugin information
     */
    public function getInfo()
    {
        // Verschiedene Info-Parameter für Plugin-Manager.
        return array(
            'version'     => $this->getVersion(),
            'label'       => $this->getLabel(),
            'author'      => 'Net Inventors GmbH',
            'link'        => 'http://www.shopinventors.de',
            'copyright'   => 'Copyright (c) 2015, Net Inventors - Agentur für digitale Medien GmbH',
            'description' => '<b>Für die Verwendung dieses Plugins benötigen Sie unser Basis-Plugin<br />'
                             .
                             '<a href="http://store.shopware.com/detail/index/sArticle/162025" target="_blank">'
                             .
                             'NetiFoundation</a> in der Version ' .
                             $this->getRequiredFoundation() .
                             ' oder höher!</b>'
        );
    }

    public function uninstall()
    {
        return true;
    }

    public function update($oldVersion)
    {
        return $this->Neti()->Setup()->quickUpdate($oldVersion);
    }

    public function install()
    {
        if (! $this->assertMinimumVersion('5.1.0')) {
            throw new RuntimeException('At least Shopware 5.1.0 is required');
        }

        $this->subscribeEvent(
            'Enlight_Controller_Front_DispatchLoopStartup',
            'onStartDispatch'
        );

        $this->Neti()->Setup()->quickInstall();

        return true;
    }

    /**
     * This callback function is triggered at the very beginning of the dispatch process and allows
     * us to register additional events on the fly. This way you won't ever need to reinstall you
     * plugin for new events - any event and hook can simply be registerend in the event subscribers
     *
     * @param Enlight_Event_EventArgs $args
     */
    public function onStartDispatch(Enlight_Event_EventArgs $args)
    {
        $this->registerMyComponents();
        $config = $this->Config();

        $subscribers = [];

        if ($config['listingProperties']) {
            $subscribers[] = new Subscriber\ListingProperties();
        }
        if ($config['globalLoginState'] || $config ['globalUserData']) {
            $subscribers[] = new Subscriber\GlobalData($config);
        }

        foreach ($subscribers as $subscriber) {
            $this->Application()->Events()->addSubscriber($subscriber);
        }
    }

    /**
     * Register autoloading namespace
     */
    public function registerMyComponents()
    {
        $this->Application()->Loader()->registerNamespace(
            'Shopware\NetiToolKit',
            $this->Path()
        );
    }

    /************************  FOUNDATION  ********************************/

    /**
     * @param bool $throwException
     *
     * @return bool
     * @throws Enlight_Exception
     */
    public function checkLicense($throwException = true)
    {
        return $this->Neti()->License()->checkLicense($throwException);
    }

    /**
     * Checks if the NetiFoundation Plugin is installed and activated
     *
     * @return boolean
     */
    public function checkNeti()
    {
        $foundation = Shopware()->Models()->getRepository('Shopware\Models\Plugin\Plugin')
                                ->findOneBy(array('name' => 'NetiFoundation', 'active' => true));

        return $foundation && version_compare($foundation->getVersion(), self::getRequiredFoundation(), '>=');
    }

    /**
     * @throws Exception
     * @return Neti
     */
    public function Neti()
    {
        if (! class_exists('Shopware_Plugins_Core_NetiFoundation_Bootstrap', false)) {
            require_once __DIR__ . '/../../Core/NetiFoundation/Bootstrap.php';
            Shopware_Plugins_Core_NetiFoundation_Bootstrap::registerAutoloader();
        }
        if (! $this->neti instanceof Neti) {
            $this->neti = new Neti($this, __DIR__);
        }

        return $this->neti;
    }

    /**
     * @param string $foo
     *
     * @return array
     */
    public function getLicense($foo = '')
    {
        return 'individual';
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return self::$moduleName;
    }

    /**
     * @return array
     */
    public function getRedmineProject()
    {
        return self::$redmine;
    }

    /**
     * @return string
     */
    public function getRequiredFoundation()
    {
        return self::$requiredFoundation;
    }
}

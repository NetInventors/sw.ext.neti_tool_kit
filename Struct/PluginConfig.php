<?php
/**
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     hrombach
 */

namespace NetiToolKit\Struct;

use NetiFoundation\Struct\AbstractClass;

class PluginConfig extends AbstractClass
{
    /**
     * @var bool - Add product properties to listing products.
     */
    protected $listingProperties = false;

    /**
     * @var bool - provide $sUserLoggedIn globally
     */
    protected $globalLoginState = true;

    /**
     * @var bool - provide $netiUserData globally
     */
    protected $globalUserData = true;

    /**
     * @return boolean
     */
    public function isListingProperties()
    {
        return $this->listingProperties;
    }

    /**
     * @return boolean
     */
    public function isGlobalLoginState()
    {
        return $this->globalLoginState;
    }

    /**
     * @return boolean
     */
    public function isGlobalUserData()
    {
        return $this->globalUserData;
    }
}
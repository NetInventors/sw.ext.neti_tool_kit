<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
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
     * @var bool - provide $netiUserData with attributes globally
     */
    protected $globalUserAttributeData;

    /**
     * @return bool
     */
    public function isListingProperties()
    {
        return $this->listingProperties;
    }

    /**
     * @return bool
     */
    public function isGlobalLoginState()
    {
        return $this->globalLoginState;
    }

    /**
     * @return bool
     */
    public function isGlobalUserData()
    {
        return $this->globalUserData;
    }

    /**
     * @return string
     */
    public function getGlobalUserAttributeData()
    {
        return $this->globalUserAttributeData;
    }
}

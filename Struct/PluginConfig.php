<?php
/**
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     hrombach
 */

namespace NetiToolKit\Struct;

class PluginConfig
{
    /**
     * @var bool - Add product properties to listing products.
     */
    private $listingProperties = false;

    /**
     * @var bool - provide $sUserLoggedIn globally
     */
    private $globalLoginState = true;

    /**
     * @var bool - provide $netiUserData globally
     */
    private $globalUserData = true;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (is_array($data)) {
            foreach ($data as $name => $value) {
                if (property_exists($this, $name)) {
                    $this->{$name} = $value;
                } else {
                    throw new \RuntimeException(sprintf('Trying to set non-existing property "%s::%s"',
                        get_class($this), $name));
                }
            }
        }
    }

    /**
     * @return boolean
     */
    public function isListingProperties()
    {
        return $this->listingProperties;
    }

    /**
     * @param boolean $listingProperties
     *
     * @return PluginConfig
     */
    public function setListingProperties($listingProperties)
    {
        $this->listingProperties = $listingProperties;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isGlobalLoginState()
    {
        return $this->globalLoginState;
    }

    /**
     * @param boolean $globalLoginState
     *
     * @return PluginConfig
     */
    public function setGlobalLoginState($globalLoginState)
    {
        $this->globalLoginState = $globalLoginState;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isGlobalUserData()
    {
        return $this->globalUserData;
    }

    /**
     * @param boolean $globalUserData
     *
     * @return PluginConfig
     */
    public function setGlobalUserData($globalUserData)
    {
        $this->globalUserData = $globalUserData;

        return $this;
    }
}
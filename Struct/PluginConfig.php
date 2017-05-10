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
    const SHOW_PROPERTIES_ON_SIMILAR_ARTICLES = 'similarArticles';
    const SHOW_PROPERTIES_ON_TOP_SELLER       = 'topSeller';
    const SHOW_PROPERTIES_ON_LISTING          = 'listing';
    const SHOW_PROPERTIES_ON_BOUGHT           = 'bought';

    /**
     * @var bool - provide $netiUserData globally
     */
    protected $globalUserData = true;

    /**
     * @var bool - provide $netiUserData with attributes globally
     */
    protected $globalUserAttributeData = false;

    /**
     * @var array - Add product properties in the following places
     *            for possible values see SHOW_PROPERTIES_ON_* class constants
     */
    protected $showPropertiesOn = ['listing'];

    /**
     * @return array
     */
    public function getShowPropertiesOn()
    {
        return $this->showPropertiesOn;
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
    public function isGlobalUserAttributeData()
    {
        return $this->globalUserAttributeData;
    }
}

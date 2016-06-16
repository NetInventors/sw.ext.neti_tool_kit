<?php

/**
 * configuration can contain models, attributes, form data, menu item(s)
 */
return array(
    'models'     => array(
        //'Shopware\CustomModels\NetiToolKit\MODELNAME'
    ),
    'attributes' => array(
        //array(
        //    'table'  => 's_order_basket_attributes',
        //    'suffix' => 'example_field',
        //    'type'   => 'varchar(11)'
        //)
    ),
    'form'       => array(
        array(
            'boolean',
            'listingProperties',
            array(
                'de_DE' => 'Artikeleigenschaften im Listing zu Verfügung stellen.',
                'en_GB' => 'Add product properties to listing products.'
            ),
            array(
                'de_DE' => 'Fügt dem sArticle-Array die Property-Sets hinzu, ' .
                           'wie sie auf der Detailseite verfügbar sind.',
                'en_GB' => 'Adds the Property sets to the sArticle array in the frontend listing, ' .
                           'the same way they are available on the Detail page.'
            ),
            false,
            Shopware\Models\Config\Element::SCOPE_SHOP
        ),
        array(
            'boolean',
            'globalLoginState',
            array(
                'de_DE' => '$sUserLoggedIn global bereitstellen',
                'en_GB' => 'provide $sUserLoggedIn globally'
            ),
            array(),
            true,
            Shopware\Models\Config\Element::SCOPE_SHOP
        ),
        array(
            'boolean',
            'globalUserData',
            array(
                'de_DE' => '$netiUserData global bereitstellen',
                'en_GB' => 'provide $netiUserData globally'
            ),
            array(),
            true,
            Shopware\Models\Config\Element::SCOPE_SHOP
        ),
    )
);

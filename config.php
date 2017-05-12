<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
 */

return [
    'redmine' => [
        'projectID' => '000000-012-447',
        'contact'   => 'hr@netinventors.de',
    ],
    'form'    => [
        [
            'type'        => 'select',
            'name'        => 'showPropertiesOn',
            'label'       => [
                'de_DE' => 'Artikeleigenschaften an folgenden Stellen zu Verfügung stellen',
                'en_GB' => 'Add product properties in the following places',
            ],
            'description' => [
                'de_DE' => 'Fügt dem sArticle-Array die Property-Sets hinzu, wie sie auf der Detailseite verfügbar sind.',
                'en_GB' => 'Adds the Property sets to the sArticle array in the frontend listing, the same way they are available on the Detail page.',
            ],
            'value'       => ['listing'],
            'scope'       => \Shopware\Models\Config\Element::SCOPE_SHOP,
            'isRequired'  => false,
            'store'       => [
                ['listing', ['de_DE' => 'Kategorieseite', 'en_GB' => 'Category page']],
                ['similarArticles', ['de_DE' => 'Ähnliche Artikel', 'en_GB' => 'Similar articles']],
                ['bought', ['de_DE' => 'Kunden kauften auch', 'en_GB' => 'Bought articles']],
                ['topSeller', ['de_DE' => 'TopSeller', 'en_GB' => 'TopSellers']],
                [
                    'emotionArticleSlider',
                    ['de_DE' => 'Artikel-Slider-Einkaufsweltelement', 'en_GB' => 'Article slider emotion component'],
                ],
            ],
            'options'     => [
                'multiSelect'    => true,
                'editable'       => false,
                'forceSelection' => false,
            ],
        ],
        [
            'boolean',
            'globalUserData',
            [
                'de_DE' => '$netiUserData global bereitstellen',
                'en_GB' => 'provide $netiUserData globally',
            ],
            [],
            true,
            Shopware\Models\Config\Element::SCOPE_SHOP,
        ],
        [
            'boolean',
            'globalUserAttributeData',
            [
                'de_DE' => '$netiUserData global mit attributes bereitstellen',
                'en_GB' => 'provide $netiUserData with attributes globally',
            ],
            [],
            false,
            Shopware\Models\Config\Element::SCOPE_SHOP,
        ],

    ],
];

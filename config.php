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
    'attributes' => [
        [
            'table'  => 's_filter_attributes',
            'prefix' => 'neti_tool_kit',
            'suffix' => 'display_in_frontend',
            'type'   => 'boolean',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Im Frontend ausgeben',
                    'en_GB' => 'Display in frontend',
                ],
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_filter_options_attributes',
            'prefix' => 'neti_tool_kit',
            'suffix' => 'display_in_frontend',
            'type'   => 'boolean',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Im Frontend ausgeben',
                    'en_GB' => 'Display in frontend',
                ],
                'displayInBackend' => true
            ]
        ],
        [
            'table'  => 's_filter_values_attributes',
            'prefix' => 'neti_tool_kit',
            'suffix' => 'display_in_frontend',
            'type'   => 'boolean',
            'data'   => [
                'label'            => [
                    'de_DE' => 'Im Frontend ausgeben',
                    'en_GB' => 'Display in frontend',
                ],
                'displayInBackend' => true,
            ],
        ],
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
                ['similarArticles', ['de_DE' => 'Ähnliche Artikel / Zubehör', 'en_GB' => 'Similar / similar articles']],
                ['bought', ['de_DE' => 'Kunden kauften auch', 'en_GB' => 'Bought articles']],
                ['topSeller', ['de_DE' => 'TopSeller', 'en_GB' => 'TopSellers']],
                [
                    'emotionArticleSlider',
                    ['de_DE' => 'Artikel-Slider-Einkaufsweltelement', 'en_GB' => 'Article slider emotion component'],
                ],
                [
                    'productStreamArticleSlider',
                    ['de_DE' => 'Product-Streams auf Artikelseite', 'en_GB' => 'Article page product streams'],
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

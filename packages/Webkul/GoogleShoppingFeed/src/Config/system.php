<?php

return [
    [
        'key'  => 'googleFeed',
        'name' => 'googleFeed::app.admin.system.googleFeed',
        'sort' => 6,
    ], [
        'key'  => 'googleFeed.settings',
        'name' => 'admin::app.admin.system.general',
        'sort' => 1,
    ], [
        'key'    => 'googleFeed.settings.general',
        'name'   => 'admin::app.admin.system.general',
        'sort'   => 1,
        'fields' => [
            // [
            //     'name'          => 'status',
            //     'title'         => 'admin::app.admin.system.status',
            //     'type'          => 'boolean',
            //     'default'       => true
            // ],
            [
                'name'          => 'google_api_key',
                'title'         => 'googleFeed::app.admin.system.google-api-key',
                'type'          => 'text'
            ],[
                'name'          => 'google_api_secret_key',
                'title'         => 'googleFeed::app.admin.system.google-api-secret-key',
                'type'          => 'password'
            ],[
                'name'          => 'google_merchant_id',
                'title'         => 'googleFeed::app.admin.system.google-merchant-id',
                'type'          => 'text'
            ],
        ],
    ], [
        'key'    => 'googleFeed.settings.defaultConfiguration',
        'name'   => 'googleFeed::app.admin.system.default-configuration.title',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'category',
                'title'         => 'googleFeed::app.admin.system.default-configuration.category',
                'type'          => 'text',
                'validation'    => 'required',
                'info'          => 'Use google taxomony i.e Apparel & Accessories link https://support.google.com/merchants/answer/6324436?hl=en'

            ],[
                'name'          => 'weight_unit',
                'title'         => 'admin::app.admin.system.weight-unit',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'lbs',
                        'value' => 'lb',
                    ], [
                        'title' => 'kgs',
                        'value' => 'kg',
                    ],
                ],
                'channel_based' => true
            ],[
                'name'          => 'age_group',
                'title'         => 'googleFeed::app.admin.system.default-configuration.age-group',
                'type'          => 'select',
                'validation'    => 'required',
                'options' => [
                    [
                        'title' => 'Adult',
                        'value' => 'adult'
                    ],[
                        'title' => 'Child',
                        'value' => 'child'
                    ]
                ]
            ],[
                'name'          => 'available_for',
                'title'         => 'googleFeed::app.admin.system.default-configuration.available-for',
                'type'          => 'select',
                'validation'    => 'required',
                'options' => [
                    [
                        'title' => 'Male',
                        'value' => 'male'
                    ],[
                        'title' => 'Female',
                        'value' => 'female'
                    ],[
                        'title' => 'Kids',
                        'value' => 'kids'
                    ]
                ]
            ],[
                'name'          => 'condition',
                'title'         => 'googleFeed::app.admin.system.default-configuration.product-condition',
                'type'          => 'select',
                'validation'    => 'required',
                'options' => [
                    [
                        'title' => 'New',
                        'value' => 'new'
                    ],[
                        'title' => 'Old',
                        'value' => 'old'
                    ]
                ]
            ],
            // [
            //     'name'          => 'tax_apply_on_ship',
            //     'title'         => 'googleFeed::app.admin.system.default-configuration.tax-apply-on-ship',
            //     'type'          => 'select',
            //     'validation'    => 'required',
            //     'options' => [
            //         [
            //             'title' => 'Yes',
            //             'value' => '1'
            //         ],[
            //             'title' => 'No',
            //             'value' => '0'
            //         ]
            //     ]
            // ],
            // [
            //     'name'          => 'tax_apply_as',
            //     'title'         => 'googleFeed::app.admin.system.default-configuration.tax-apply-as',
            //     'type'          => 'depends',
            //     'validation'    => 'required',
            //     'depend' => 'tax_apply_on_ship:1',
            //     'options' => [
            //         [
            //             'title' => 'Default',
            //             'value' => '0'
            //         ],[
            //             'title' => 'As Product',
            //             'value' => '1'
            //         ]
            //     ],
            // ],
            // [
            //     'name'          => 'tax_rate',
            //     'title'         => 'googleFeed::app.admin.system.default-configuration.tax-rate',
            //     'type'          => 'depends',
            //     'depend' => 'tax_apply_as:0',
            //     'note'          =>  'Fill on tax apply as default',

            // ],
             [
                'name'          => 'target_country',
                'title'         => 'googleFeed::app.admin.system.default-configuration.target-country',
                'type'          => 'select',
                'validation'    => 'required',
                'options' => [
                    [ 'title' => 'Australia', 'value' => 'AU'
                    ],
                    [ 'title' => 'Austria', 'value' => 'AT'
                    ],
                    [ 'title' => 'Belgium', 'value' => 'BE'
                    ],
                    [ 'title' => 'Brazil', 'value' => 'BR'
                    ],
                    [ 'title' => 'Canada', 'value' => 'CA'
                    ],
                    [ 'title' => 'the Czech Republic', 'value' => 'CZ'
                    ],
                    [ 'title' => 'Denmark',  'value' => 'DK'
                    ],
                    [ 'title' => 'France', 'value' => 'FR'
                    ],
                    [ 'title' => 'Germany', 'value' => 'DE'
                    ],
                    [ 'title' => 'India', 'value' => 'IN'
                    ],
                    [ 'title' => 'Ireland', 'value' => 'IE'
                    ],
                    [ 'title' => 'Italy', 'value' => 'IT'
                    ],
                    [ 'title' => 'Japan', 'value' => 'JP'
                    ],
                    [ 'title' => 'Mexico', 'value' => 'MX'
                    ],
                    [ 'title' => 'Norway', 'value' => 'NO'
                    ],
                    [ 'title' => 'Poland', 'value' => 'PL'
                    ],
                    [ 'title' => 'New Zealand', 'value' => 'NZ'
                    ],
                    [ 'title' => 'the Netherlands', 'value' => 'NL'
                    ],
                    [ 'title' => 'Russia', 'value' => 'RU'
                    ],
                    [ 'title' => 'Singapore', 'value' => 'SG'
                    ],
                    [ 'title' => 'South Africa', 'value' => 'ZA'
                    ],
                    [ 'title' => 'Spain', 'value' => 'ES'
                    ],
                    [ 'title' => 'Sweden', 'value' => 'SE'
                    ],
                    [ 'title' => 'Switzerland', 'value' => 'CH'
                    ],
                    [ 'title' => 'Turkey', 'value' => 'TR'
                    ],
                    [ 'title' => 'United Kingdom of Great Britain', 'value' => 'GB'
                    ],
                    [ 'title' => 'United States of America', 'value' => 'US'
                    ],
                ]
            ]
        ]
    ]

];
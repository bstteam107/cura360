<?php

return [
    [
        'key'  => 'suggestion',
        'name' => 'Search Suggestion',
        'sort' => 1,
    ],[
        'key'  => 'suggestion.suggestion',
        'name' => 'Search Suggestion Settings',
        'sort' => 1,
    ],[
        'key'    => 'suggestion.suggestion.options',
        'name'   => 'Options',
        'sort'   => 1,
        'fields' => [
            [   
                'name'          => 'show_terms',
                'title'         => 'suggestion::app.admin.configuration.show-categories',
                'type'          => 'text',
                'validation'    => 'required|numeric|min:1|max:5',
                'channel_based' => true,
            ],[
                'name'          => 'show_products',
                'title'         => 'suggestion::app.admin.configuration.show-products',
                'type'          => 'text',
                'validation'    => 'required|numeric|min:1|max:5',
                'channel_based' => true,
            ],[
                'name'          => 'display_terms_toggle',
                'title'         => 'suggestion::app.admin.configuration.display-terms',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],[
                'name'          => 'display_product_toggle',
                'title'         => 'suggestion::app.admin.configuration.display-product',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],[
                'name'          => 'display_categories_toggle',
                'title'         => 'suggestion::app.admin.configuration.display-categories',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ],
];
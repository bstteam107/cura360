<?php

return [
    'admin' => [
        'layouts' => [
            'googleFeed' => 'Google Shopping Feed',
            'category'   => 'Map Category',
            'product'    => 'Products',
            'attribute'  => 'Map Attribute',
            'export'     => 'Export Product',
            'products'   => 'Exported Products',

            'settings'   => [
                'title'    => 'Google Shopping Feed',
                'auth'     => 'Authenticate Account',
                'auth-btn' => 'Authenticate',
                'auth-refresh-btn' => 'Refresh Token',
                'api-key'  => 'Oauth API Key',
                'api-secret-key'  => 'Oauth API Secret Key',
                'auth-success' => 'Authenticated successfully',
                'refreshed-token' => 'Access token has been refresh successfully'
            ]


        ],
        'system' => [
            'googleFeed'            => 'Google Shopping Feed',
            'google-api-key'        => 'Google Oauth Key',
            'google-api-secret-key' => 'Google Oauth Secret Key',
            'google-merchant-id'    => 'Google Shop Merchant ID',

            'default-configuration' => [
                'title'             => 'Default Configuration',
                'category'          => 'Default Category',
                'weight-unit'       => 'Weight Unit',
                'age-group'         => 'Age Group',
                'available-for'     => 'Product Available For',
                'product-condition' => 'Product Condition',
                'tax-apply-on-ship' => 'Tax Apply On Ship',
                'tax-apply-as'      => 'Shpping Tax Apply As',
                'tax-rate'          => 'Tax Rate',
                'target-country'    => 'Target Country'
            ]
        ],

        'attribute' => [
            'product-id'        => 'Product ID',
            'title-id'          => 'Title',
            'description-id'    => 'Description',
            'gtin-id'           => 'GTIN',
            'brand-id'          => 'Brand',
            'color-id'          => 'Color',
            'weight-id'         => 'Shipping Weight',
            'image-id'          => 'Image link',
            'size-id'           => 'Size',
            'size-system-id'    => 'Size System',
            'size-type-id'      => 'Size Type',
            'mpn-id'            => 'MPN',
            'save-btn-title'    => 'SAVE',
            'refresh-btn-title' => 'Refresh',
            'title'             => 'Map Google Product Attribute',
            'save-success'      => 'Mapped attributes saved successfully',
            'update-success'    => 'Mapped attributes updated successfully',
            'delete-success'    => 'Refreshed successfully'
        ],

        'map-categories' => [
            'title'                         => 'Map Google Categories',
            'map-btn-title'                 => 'Map new Category',
            'add-title'                     => 'Map New Category',
            'entry-choose-bagisto-category' => 'Choose Store Category',
            'entry-select-bagisto-category' => 'Select store category',
            'entry-choose-origin-category'  => 'Choose Google Category',
            'success-save'                  => 'Category mapped successfully',
            'success-delete'                => 'Mapped category has been deleted successfully',
            'store-name'                    => 'Store Category Name',
            'google-name'                   => 'Google Category Name'
        ],

        'product' => [
            'export'  => 'Export to Google Shop',
            'refresh' => 'Your access token has been expired. Please refresh your token',
            'wrong'   =>  'Something went wrong. Please try again'
        ]
    ]
];
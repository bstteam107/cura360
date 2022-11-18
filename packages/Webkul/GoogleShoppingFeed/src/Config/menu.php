<?php

return [
    [
        'key'        => 'googleFeed',
        'name'       => 'googleFeed::app.admin.layouts.googleFeed',
        'route'      => 'googleFeed.attribute.index',
        'sort'       => 3,
        'icon-class' => 'google-icon',
    ],[
        'key'        => 'googleFeed.auth',
        'name'       => 'googleFeed::app.admin.layouts.settings.auth',
        'route'      => 'googleFeed.account.auth',
        'sort'       => 1
    ],[
        'key'        => 'googleFeed.attribute',
        'name'       => 'googleFeed::app.admin.layouts.attribute',
        'route'      => 'googleFeed.attribute.index',
        'sort'       => 2
    ],[
        'key'        => 'googleFeed.category',
        'name'       => 'googleFeed::app.admin.layouts.category',
        'route'      => 'googleFeed.category.index',
        'sort'       => 2
    ],[
        'key'        => 'googleFeed.product',
        'name'       => 'googleFeed::app.admin.layouts.product',
        'route'      => 'googleFeed.products.export.index',
        'sort'       => 3
    ]
    // ,[
    //     'key'        => 'googleFeed.product.export',
    //     'name'       => 'googleFeed::app.admin.layouts.export',
    //     'route'      => 'googleFeed.products.export.index',
    //     'sort'       => 1
    // ],[
    //     'key'        => 'googleFeed.product.index',
    //     'name'       => 'googleFeed::app.admin.layouts.product',
    //     'route'      => 'googleFeed.product.index',
    //     'sort'       => 2
    // ]
];
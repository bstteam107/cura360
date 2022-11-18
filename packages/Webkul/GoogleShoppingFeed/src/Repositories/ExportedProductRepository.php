<?php

namespace Webkul\GoogleShoppingFeed\Repositories;

use Webkul\Core\Eloquent\Repository;


class ExportedProductRepository extends Repository
{


    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\GoogleShoppingFeed\Contracts\ExportedProduct';
    }
}
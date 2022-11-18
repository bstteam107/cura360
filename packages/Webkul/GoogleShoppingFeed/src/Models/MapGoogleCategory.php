<?php

namespace Webkul\GoogleShoppingFeed\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\GoogleShoppingFeed\Contracts\MapGoogleCategory as MapGoogleCategoryContract;

class MapGoogleCategory extends Model implements MapGoogleCategoryContract
{
    protected $guarded = ['id'];
}

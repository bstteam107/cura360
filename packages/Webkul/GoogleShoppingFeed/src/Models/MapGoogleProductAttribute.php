<?php

namespace Webkul\GoogleShoppingFeed\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\GoogleShoppingFeed\Contracts\MapGoogleProductAttribute as MapGoogleProductAttributeContract;

class MapGoogleProductAttribute extends Model implements MapGoogleProductAttributeContract
{
    protected $guarded = ['id'];
}

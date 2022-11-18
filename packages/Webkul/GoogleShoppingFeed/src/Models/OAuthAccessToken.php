<?php

namespace Webkul\GoogleShoppingFeed\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\GoogleShoppingFeed\Contracts\OAuthAccessToken as OAuthAccessTokenContract;

class OAuthAccessToken extends Model implements OAuthAccessTokenContract
{
    protected $guarded = ['id'];
}

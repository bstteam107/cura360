<?php

namespace App\Models\Category;

//use Illuminate\Database\Eloquent\Model;

//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Webkul\Category\Contracts\CategoryTranslation as CategoryTranslationContract;
//use App\Models\Model;
use Webkul\Category\Models\CategoryTranslation as CategoryTranslationBaseModel;
/**
 * Class CategoryTranslation
 *
 * @package Webkul\Category\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class CategoryTranslation extends CategoryTranslationBaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
		'longdescription',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
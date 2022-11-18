<?php

namespace App\Models\Category;

use Webkul\Category\Models\Category as CategoryBaseModel;

class Category extends CategoryBaseModel
{
  /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
		'longdescription',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}

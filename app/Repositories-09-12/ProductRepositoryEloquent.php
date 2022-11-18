<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProductRepositoryRepository;
use App\Entities\ProductRepository;
use App\Validators\ProductRepositoryValidator;

/**
 * Class ProductRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepositoryRepositoryEloquent extends BaseRepository implements ProductRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductRepository::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

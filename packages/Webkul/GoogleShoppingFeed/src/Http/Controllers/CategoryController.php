<?php

namespace Webkul\GoogleShoppingFeed\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\GoogleShoppingFeed\Repositories\MapGoogleCategoryRepository;

class CategoryController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;


    /**
     * Holds the list of all google category from local file
     */
    protected $googleCategory;


    /**
     * CategoryRepository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * MapGooleCategoryRepository object
     *
     * @var \Webkul\GoogleShoppingFeed\Repositories\MapGoogleCategoryRepository
     */
    protected $mapGoogleCategoryRepository;


    public function __construct
    (
        CategoryRepository $categoryRepository,
        MapGoogleCategoryRepository $mapGoogleCategoryRepository
    )
    {
        $this->_config = request('_config');
        $this->middleware('admin');
        $this->categoryRepository = $categoryRepository;

        $this->mapGoogleCategoryRepository = $mapGoogleCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->googleCategory = (new DataGridImport)->toArray(__DIR__ . '/../../Data/category.ods');
        $rootCategoryId  = core()->getCurrentChannel()->root_category_id;
        $storeCategory  = $this->categoryRepository->getVisibleCategoryTree($rootCategoryId);
        $googleCategory =  $this->googleCategory[0];


        return view($this->_config['view'],compact('googleCategory', 'storeCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $requestData = request()->all();

        if ($requestData['bagisto_category'][0] == "") {
            session()->flash('error', 'Please select store category');

            return redirect()->back();
        }

        if ($requestData['origin_category'][0] == "") {
            session()->flash('error', 'Please select google category');

            return redirect()->back();
        }

        $data = [
            'category_id' => end($requestData['bagisto_category']) ? end($requestData['bagisto_category']) :$requestData['bagisto_category'][count($requestData['bagisto_category'])-2]  ,
            'google_category_path' => implode(' > ', $requestData['origin_category'])
        ];

        $this->mapGoogleCategoryRepository->create($data);

        session()->flash('success', __('googleFeed::app.admin.map-categories.success-save') );

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $indexes = explode(',',request()->indexes);

        $categories = $this->mapGoogleCategoryRepository->findWhereIn('id',$indexes);

        foreach($categories as $category) {
            $category->delete();
        }

        session()->flash('success', __('googleFeed::app.admin.map-categories.success-delete'));

        return redirect()->back();
    }

}

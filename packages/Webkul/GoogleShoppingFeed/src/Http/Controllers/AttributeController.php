<?php

namespace Webkul\GoogleShoppingFeed\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\GoogleShoppingFeed\Repositories\MapGoogleProductAttributeRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\GoogleShoppingFeed\Http\Request\MapAttribute;

class AttributeController extends Controller
{

    /**
     * MapGoogleProductRepository object
     * @var $mapGoogleProductRepository
     */
    protected $mapGoogleProductAttributeRepository;

    /**
     * AttributeRepository object
     * @var $attributeRepository
     */
    protected $attributeRepository;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    public function __construct(
        MapGoogleProductAttributeRepository $mapGoogleProductAttributeRepository,
        AttributeRepository $attributeRepository
    )
    {
        $this->_config = request('_config');
        $this->middleware('admin');
        $this->mapGoogleProductAttributeRepository = $mapGoogleProductAttributeRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $googleProductAttribute = $this->mapGoogleProductAttributeRepository->first();

        $attributes = $this->attributeRepository->all();

        return view($this->_config['view'], compact('googleProductAttribute', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MapAttribute $request)
    {
        // dd(request()->all());
        $this->mapGoogleProductAttributeRepository->create(request()->all());

        session()->flash('success', __('googleFeed::app.admin.attribute.save-success'));

        return redirect()->back();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MapAttribute $request, $id)
    {
        $googleProductAttribute = $this->mapGoogleProductAttributeRepository->find($id);
        $googleProductAttribute->update(request()->all());

        session()->flash('success', __('googleFeed::app.admin.attribute.update-success'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $googleProductAttribute = $this->mapGoogleProductAttributeRepository->find($id);
        $googleProductAttribute->delete();

        session()->flash('success', __('googleFeed::app.admin.attribute.delete-success'));

        return redirect()->back();
    }
}

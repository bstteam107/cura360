<?php

namespace Webkul\GoogleShoppingFeed\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class MapCategoryDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('map_google_categories as cat')
            ->select('cat.id', 'cat.category_id','ct.name', 'cat.google_category_path')
            ->leftJoin('category_translations as ct', function($leftJoin) {
                $leftJoin->on('cat.category_id', '=', 'ct.category_id')
                         ->where('ct.locale', app()->getLocale());
            });

        $this->addFilter('id', 'cat.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('googleFeed::app.admin.map-categories.store-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'google_category_path',
            'label'      => trans('googleFeed::app.admin.map-categories.google-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'action' => route('googleFeed.category.mass-delete'),
            'label'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
        ]);
    }
}
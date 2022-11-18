@extends('admin::layouts.content')

@section('page_title')
    {{ __('googleFeed::app.admin.map-categories.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('googleFeed::app.admin.map-categories.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('googleFeed.category.map.create') }}" class="btn btn-lg btn-primary">
                    {{ __('googleFeed::app.admin.map-categories.map-btn-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.before') !!}

        <div class="page-content">
            {!! app('Webkul\GoogleShoppingFeed\DataGrids\MapCategoryDataGrid')->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.catalog.categories.list.after') !!}
    </div>
@stop
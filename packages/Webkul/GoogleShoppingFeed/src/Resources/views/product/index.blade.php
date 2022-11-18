@extends('admin::layouts.content')

@section('page_title')
    {{ __('googleFeed::app.admin.layouts.products') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('googleFeed::app.admin.layouts.products') }}</h1>
            </div>
            <div class="page-action">
                {{-- <div class="export-import" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>
                    <span >
                        {{ __('admin::app.export.export') }}
                    </span>
                </div>

                <a href="{{ route('admin.cms.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.cms.pages.add-title') }}
                </a> --}}
            </div>
        </div>

        <div class="page-content">
            @inject('cmsGrid', 'Webkul\Admin\DataGrids\CMSPageDataGrid')

            {!! $cmsGrid->render() !!}
        </div>
    </div>

@stop



@extends('admin::layouts.content')

@section('title')
    {{ __('googleFeed::app.admin.layouts.settings.auth') }}
@stop

@section('content')

    <div class="content">
        <form method="POST" action="{{ route('googleFeed.account.authenticate') }}" @submit.prevent="onSubmit">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('googleFeed::app.admin.layouts.settings.auth') }}</h1>
            </div>
            <div class="page-action">

                <button type="submit" class="btn btn-lg btn-primary">
                    {{ __('googleFeed::app.admin.layouts.settings.auth-btn') }}
                </button>

                <a class="btn btn-lg btn-primary" href="{{route('googleFeed.account.authenticate.refresh')}}">{{ __('googleFeed::app.admin.layouts.settings.auth-refresh-btn') }}</a>
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                @csrf()
                <div slot="body">
                    <div class="control-group" :class="[errors.has('api_key') ? 'has-error' : '']">
                        <label for="api_key" class="required">{{ __('googleFeed::app.admin.layouts.settings.api-key') }}</label>

                        <input type="text" class="control" name="api_key" v-validate="'required'" value="{{core()->getConfigData('googleFeed.settings.general.google_api_key')}}" data-vv-as="&quot;{{ __('googleFeed::app.admin.layouts.settings.api-key') }}&quot;" readonly >

                        <span class="control-error" v-if="errors.has('api_key')">@{{ errors.first('api_key') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('api_secret_key') ? 'has-error' : '']">
                        <label for="api_secret_key" class="required">{{ __('googleFeed::app.admin.layouts.settings.api-key') }}</label>

                        <input type="password" class="control" name="api_secret_key_secret" v-validate="'required'" value="{{core()->getConfigData('googleFeed.settings.general.google_api_secret_key')}}" data-vv-as="&quot;{{ __('googleFeed::app.admin.layouts.settings.api-key') }}&quot;" readonly >

                        <span class="control-error" v-if="errors.has('api_secret_key')">@{{ errors.first('api_secret_key') }}</span>
                    </div>
                </div>
            </div>

        </div>
        </form>
    </div>
@stop




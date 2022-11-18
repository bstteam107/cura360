@extends('admin::layouts.content')

@section('page_title')
{{ __('googleFeed::app.admin.attribute.title') }}
@stop

@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>{{__('googleFeed::app.admin.attribute.title') }}</h1>
        </div>
        <div class="page-action">
            @if ( ! is_null($googleProductAttribute))
                <a href="{{ route('googleFeed.attribute.index.refresh', $googleProductAttribute->id) }}" class="btn btn-lg btn-primary">
                    {{ __('googleFeed::app.admin.attribute.refresh-btn-title') }}
                </a>
            @endif
        </div>
    </div>

    <div class="page-content">
    @if(is_null($googleProductAttribute))
        <form method="POST" action="{{ route('googleFeed.attribute.index.store') }}">
    @else
        <form method="POST"
        action="{{ route('googleFeed.attribute.index.update', $googleProductAttribute->id) }}">
    @endif

            <div class="form-container">
                @csrf()

                <div slot="body">

                    <div class="control-group" :class="[errors.has('product_id') ? 'has-error' : '']">
                        <label for="product_id"
                            class="required">{{ __('googleFeed::app.admin.attribute.product-id') }}</label>
                        <select name="product_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->product_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('admin::app.customers.customers.product-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->product_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('product_id')">@{{ errors.first('product_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('title_id') ? 'has-error' : '']">
                        <label for="title_id"
                            class="required">{{ __('googleFeed::app.admin.attribute.title-id') }}</label>
                        <select name="title_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->title_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.title-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->title_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('title_id')">@{{ errors.first('title_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('description_id') ? 'has-error' : '']">
                        <label for="description_id"
                            class="required">{{ __('googleFeed::app.admin.attribute.description-id') }}</label>
                        <select name="description_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->description_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.description-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}"  @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->description_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('description_id')">@{{ errors.first('description_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('gtin_id') ? 'has-error' : '']">
                        <label for="gtin_id"
                            class="">{{ __('googleFeed::app.admin.attribute.gtin-id') }}</label>
                        <select name="gtin_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->gtin_id  }}"
                            v-validate="''"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.gtin-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                            <option value="{{$attribute->code}}"  @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->gtin_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('gtin_id')">@{{ errors.first('gtin_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('brand_id') ? 'has-error' : '']">
                        <label for="brand_id"
                            class="required">{{ __('googleFeed::app.admin.attribute.brand-id') }}</label>
                        <select name="brand_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->brand_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.brand-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                            <option value="{{$attribute->code}}"  @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->brand_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('brand_id')">@{{ errors.first('brand_id') }}</span>
                    </div>


                    <div class="control-group" :class="[errors.has('color_id') ? 'has-error' : '']">
                        <label for="color_id"
                            class="required">{{ __('googleFeed::app.admin.attribute.color-id') }}</label>
                        <select name="color_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->color_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.color-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->color_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('color_id')">@{{ errors.first('color_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('weight_id') ? 'has-error' : '']">
                        <label for="weight_id"
                            class="required">{{ __('googleFeed::app.admin.attribute.weight-id') }}</label>
                        <select name="weight_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->weight_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.weight-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->weight_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('weight_id')">@{{ errors.first('weight_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('image_id') ? 'has-error' : '']">
                        <label for="image_id"
                         >{{ __('googleFeed::app.admin.attribute.image-id') }}</label>
                        <select name="image_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->image_id  }}"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.image-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->image_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('image_id')">@{{ errors.first('image_id') }}</span>
                    </div>


                    <div class="control-group" :class="[errors.has('size_id') ? 'has-error' : '']">
                        <label for="size_id"
                            class="">{{ __('googleFeed::app.admin.attribute.size-id') }}</label>
                        <select name="size_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->size_id  }}"
                            v-validate="''"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.size-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->size_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('size_id')">@{{ errors.first('size_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('size_system_id') ? 'has-error' : '']">
                        <label for="size_system_id"
                            class="">{{ __('googleFeed::app.admin.attribute.size-system-id') }}</label>
                        <select name="size_system_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->size_system_id  }}"
                            v-validate="''"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.size-system-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->size_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('size_system_id')">@{{ errors.first('size_system_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('size_type_id') ? 'has-error' : '']">
                        <label for="size_type_id"
                            class="">{{ __('googleFeed::app.admin.attribute.size-type-id') }}</label>
                        <select name="size_type_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->size_type_id  }}"
                            v-validate="''"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.size-type-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->size_system_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('size_type_id')">@{{ errors.first('size_type_id') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('mpn_id') ? 'has-error' : '']">
                        <label for="mpn_id"
                            class="">{{ __('googleFeed::app.admin.attribute.mpn-id') }}</label>
                        <select name="mpn_id" class="control" value="{{ is_null($googleProductAttribute) ? '' : $googleProductAttribute->mpn_id  }}"
                            v-validate="''"
                            data-vv-as="&quot;{{ __('googleFeed::app.admin.attribute.mpn-id') }}&quot;">

                            <option value=""></option>
                            @foreach ($attributes as $attribute)
                                 <option value="{{$attribute->code}}" @if (! is_null($googleProductAttribute)) {{ $googleProductAttribute->mpn_id == $attribute->code ? 'selected' : '' }} @endif> {{$attribute->name}}
                                </option>
                            @endforeach

                        </select>
                        <span class="control-error"
                            v-if="errors.has('mpn_id')">@{{ errors.first('mpn_id') }}</span>
                    </div>

                </div>
                <button type="submit" class="btn btn-lg btn-primary">
                    {{ __('googleFeed::app.admin.attribute.save-btn-title') }}
                </button>
            </div>

        </form>
    </div>
</div>


@stop
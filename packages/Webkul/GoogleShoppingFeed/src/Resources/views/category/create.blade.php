@extends('admin::layouts.content')

@section('page_title')
    {{ __('googleFeed::app.admin.map-categories.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('googleFeed.category.map.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('googleFeed::app.admin.map-categories.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.save') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <div class="control-group" v-bind:style="{'width': '100%'}">
                        <label for="store-categories" class="required" style="{'margin-top': '20px'}">{{ __('googleFeed::app.admin.map-categories.entry-choose-bagisto-category') }}</label>

                        <store-category-map></store-category-map>

                    </div>

                     <div class="control-group" v-bind:style="{'width': '100%'}">
                        <label for="ebay-categories" class="required" style="{'margin-top': '20px'}">{{ __('googleFeed::app.admin.map-categories.entry-choose-origin-category') }}</label>

                        <origin-category-map></origin-category-map>

                    </div>
                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
<script type="text/x-template" id="store-category-map-template">
    <div class="map_bagisto_category" v-bind:style="{'width': '100%'}">
        <store-category-dropdown v-for="(categories, index) in bagisto_categories" :categories="categories" :key="index" :index="index"></store-category-dropdown>
    </div>
</script>

<script type="text/x-template" id="store-category-dropdown-template">
    <div class="category-map-panel" v-bind:style="{'display': 'inline-block', 'margin-right': '10px'}">
        <select class="control bagisto_category" id="bagisto_map_category" name="bagisto_category[]" v-model="bagisto_selected" @change="addDropdown($event)" v-bind:style="{'width': 'auto', 'display': 'inline-block'}">

            <option value="">{{ __('googleFeed::app.admin.map-categories.entry-select-bagisto-category') }}</option>

            <option v-for="category in categories" :value="category.id" v-text="category.name"></option>
        </select>
    </div>
</script>

<script>
    var store_categories = @json($storeCategory);


    var bagisto_categories = [];
    var origin_categories = [];

    $(document).ready(function(){
        $('.map_category').on('click', () => {
            $('.mapping_area').toggle('slow');
        });
    });

    Vue.component('store-category-dropdown', {
        template: '#store-category-dropdown-template',

        props: ['index', 'categories'],

        data() {
            return {
                bagisto_selected: '',
            }
        },
        methods: {
            addDropdown(event) {
                var thisthis = this;

                $(event.target).parent().nextAll().remove();

                $.each(thisthis.categories, (key, category) => {
                    if (category.id == thisthis.bagisto_selected) {
                        if ( category.children && Object.keys(category.children).length > 0) {
                            bagisto_categories.push(category.children);
                        }
                    }
                });
                // console.log(thisthis.bagisto_selected)
            }
        }
    });

    Vue.component('store-category-map', {
        template: '#store-category-map-template',

        inject: ['$validator'],

        data() {
            return {
                bagisto_categories: bagisto_categories,
            }
        },
        mounted: function() {
            this.manageBagistoRootCategory();
        },
        methods: {
            manageBagistoRootCategory() {

                var thisthis = this;

                let root_categorys = [];
                $.each(store_categories, (key, category) => {
                    root_categorys.push(category);
                });

                bagisto_categories.push(root_categorys);
            }
        }
    });

</script>

<script type="text/x-template" id="origin-category-map-template">
    <div>
        <div class="map_origin_category" v-bind:style="{'width': '100%'}">

            <origin-category-dropdown  v-for="(categories, index) in origin_categories" :categories="categories" :key="index" :index="index"></origin-category-dropdown>



        </div>
    </div>
</script>

<script type="text/x-template" id="origin-category-dropdown-template">
    <div class="origin-category-map-panel" v-bind:style="{'display': 'inline-block', 'margin-right': '10px'}">
        <select class="control bagisto_category" id="origin_map_category" name="origin_category[]" v-model="origin_selected" @change="addDropdown($event)" v-bind:style="{'width': 'auto', 'display': 'inline-block'}">

            <option value="">{{ __('googleFeed::app.admin.map-categories.entry-select-bagisto-category') }}</option>

            <option v-for="category in categories"  >
                @{{category}}
            </option>
        </select>

        <span class="control-error" v-if="errors.has('origin_category')">@{{ errors.first('origin_category') }}</span>
    </div>

</script>

<script>

    Vue.component('origin-category-dropdown', {
        template: '#origin-category-dropdown-template',

        props: ['index', 'categories'],

        data() {
            return {
                origin_selected: '',
            }
        },
        methods: {
            addDropdown(event) {
                var thisthis = this;
                $(event.target).parent().nextAll().remove();

                let sub = [];
                $.each(@json($googleCategory), (key, category) => {
                let subkey = this.getKeyByValue(category, thisthis.origin_selected)
                    if ( category[subkey] == thisthis.origin_selected) {
                        if (subkey == 'root') {
                            if (! (sub.find( element => element == category.sub1))) {
                                if (category.sub1) {
                                 sub.push(category.sub1)
                                }
                            }
                        } else {
                            let keyIndex = 'sub'+ (parseInt(subkey.charAt(3))+1).toString()
                            console.log(keyIndex)
                            if (! (sub.find( element => element == category[keyIndex]))) {

                                if (category[keyIndex]) {
                                    sub.push(category[keyIndex])
                                }
                            }
                        }

                    }
                });

                if (sub.length > 0) {
                    origin_categories.push(sub);
                }

            },

            getKeyByValue(category, value) {
                return Object.keys(category).find(key => category[key] === value);
            }
        }
    });

    Vue.component('origin-category-map', {
        template: '#origin-category-map-template',

        inject: ['$validator'],

        data() {
            return {
                origin_categories: origin_categories,
                company_selected: ''
            }
        },
        mounted: function() {
            this.manageBagistoRootCategory();
        },
        methods: {

            manageBagistoRootCategory() {

            var thisthis = this;

            let root_categorys = [];
            $.each(@json($googleCategory), (key, category) => {

                if (! (root_categorys.find( element => element == category.root))) {
                        root_categorys.push(category.root);
                }

            });

            origin_categories.push(root_categorys);
            },

            saveCategoryMapping() {
                var thisthis = this;
            }
        }
    });
</script>


@endpush
<div class="input-group">
    <form
        method="GET"
        role="search"
        id="search-form"
        action="{{ route('velocity.search.index') }}">
        <div
            class="btn-toolbar full-width search-form"
            role="toolbar">

            <suggestion-searchbar-component>
                <template v-slot:image-search>
                    <image-search-component
                        status="{{core()->getConfigData('general.content.shop.image_search') == '1' ? 'true' : 'false'}}"
                        upload-src="{{ route('shop.image.search.upload') }}"
                        view-src="{{ route('shop.search.index') }}"
                        common-error="{{ __('shop::app.common.error') }}"
                        size-limit-error="{{ __('shop::app.common.image-upload-limit') }}">
                    </image-search-component>
                </template>
            </suggestion-searchbar-component>

        </div>
    </form>
</div>

@push('scripts')

<script type="text/x-template" id="suggestionSearchBar">
    <template>
        <div>
            <div class="btn-group full-width force-center">
                <div class="selectdiv">
                    <select
                        class="form-control fs13 styled-select"
                        name="category"
                        aria-label="Category"
                        @change="focusInput($event)"
                    >
                        <option value="" v-text="__('header.all-categories')"></option>

                        <template
                            v-for="(category, index) in $root.sharedRootCategories"
                        >
                            <option
                                :key="index"
                                selected="selected"
                                :value="category.id"
                                v-if="category.id == searchedQuery.category"
                                v-text="category.name"
                            >
                            </option>

                            <option
                                :key="index"
                                :value="category.id"
                                v-text="category.name"
                                v-else
                            ></option>
                        </template>
                    </select>

                    <div class="select-icon-container d-inline-block float-right">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>

                <input
                    required
                    name="term"
                    type="search"
                    class="form-control"
                    @keyup="someFunctionRun($event)"
                    :placeholder="__('header.search-text')"
                    aria-label="Search"
                    v-model:value="inputVal"
                />
                
                <slot name="image-search"></slot>

                <button
                    class="btn"
                    type="button"
                    id="header-search-icon"
                    aria-label="Search"
                    @click="submitForm"
                >
                    <i class="fs16 fw6 rango-search"></i>
                </button>
            </div>
            <div id="old-new" class="suggests full-width"></div>
        </div>
    </template>
</script>

<script>
    var url = window.location.toString();

    if(url.match(/%3Cb%3E/gi) == '%3Cb%3E'){
        var urla = url.replace('%3Cb%3E','');
        window.location = urla.replace('%3C/b%3E',''); 
    }

</script>

<script type="text/javascript">
    (() => {
        Vue.component('suggestion-searchbar-component', {
            'template': '#suggestionSearchBar',
            
            data: function () {
                return {
                    inputVal: '',
                    searchedQuery: []
                };
            },
           
            created: function() {
                let searchedItem = window.location.search.replace('?', '');
                searchedItem = searchedItem.split('&');

                let updatedSearchedCollection = {};

                searchedItem.forEach(item => {
                    let splitedItem = item.split('=');
                    updatedSearchedCollection[splitedItem[0]] = decodeURI(
                        splitedItem[1]
                    );
                });

                if (updatedSearchedCollection['image-search'] == 1) {
                    updatedSearchedCollection.term = '';
                }

                this.searchedQuery = updatedSearchedCollection;

                if (this.searchedQuery.term) {
                    this.inputVal = decodeURIComponent(
                        this.searchedQuery.term.split('+').join(' ')
                    );
                }
            },

            methods: {
                'someFunctionRun' : function(event) {

                    var display_product_toggle = '<?php $data = core()->getConfigData('suggestion.suggestion.options.display_product_toggle'); echo $data ?>';   

                    var no_of_terms = '<?php $data = core()->getConfigData('suggestion.suggestion.options.show_terms'); echo $data ?>';
    
                    var display_terms_toggle = '<?php $data = core()->getConfigData('suggestion.suggestion.options.display_terms_toggle'); echo $data ?>';

                    var no_of_products = '<?php $data = core()->getConfigData('suggestion.suggestion.options.show_products'); echo $data ?>';

                    var display_terms_number_toggle = '<?php $data = core()->getConfigData('suggestion.suggestion.options.display_terms_number_toggle'); echo $data ?>';

                    var display_category = '<?php $data = core()->getConfigData('suggestion.suggestion.options.display_categories_toggle'); echo $data ?>';

                    var display = '<?php $locale = core()->getCurrentLocale()->code; echo $locale ?>';

                    var term =event.target.value;

                    var obj = {
                                category:'',
                                term:term
                    };

                    $.ajax({
                        url:"{{ route('searchsuggestion.search.index') }}",
                        type:"get",
                        data:obj,

                        success:function(data) {
                            $('.suggests').html(''); 
                            if(term.length !== 0) {
                                if(data[0].data.length !== 0) {
                                    if(display_category == 1) {
                                        if(data[0].data.length < no_of_terms) {
                                            for (let index = 0; index < data[0].data.length; index++) {
                                                if(data[0].data[index].product.categories[0]) {
                                                    if(data[0].data[index].product.categories[0].name == 'Root') {
                                                        $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                            'href="'+data[0].data[index].url_key+'"> <div class="velocity_category">'+
                                                            '<p>'+data[0].data[index].name+'</p>'+
                                                            '</div></a>'
                                                        );     
                                                    } else {
                                                        $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                            'href="'+data[0].data[index].url_key+'"> <div class="velocity_category">'+
                                                            '<p>'+data[0].data[index].name+' {{ __('suggestion::app.shop.seach-suggestion.in') }} '+data[0].data[index].product.categories[0].name+'</p>'+
                                                            '</div></a>'
                                                        );
                                                    }
                                                } else {
                                                    $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                        'href="'+data[0].data[index].url_key+'"> <div class="velocity_category">'+
                                                        '<p>'+data[0].data[index].name+'</p>'+
                                                        '</div></a>'
                                                    );
                                                }
                                            }

                                        } else if (data[0].data.length >= no_of_terms) {
                                            for (let index = 0; index < no_of_terms; index++) {
                                                if(data[0].data[index].product.categories[0]) {
                                                    if(data[0].data[index].product.categories[0].name == 'Root') {

                                                        $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                            'href="'+data[0].data[index].url_key+'"> <div class="velocity_category">'+
                                                            '<p>'+data[0].data[index].name+'</p>'+
                                                            '</div></a>'
                                                        );     
                                                    } else {
                                                        $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                            'href="'+data[0].data[index].url_key+'"> <div class="velocity_category">'+
                                                            '<p>'+data[0].data[index].name+' {{ __('suggestion::app.shop.seach-suggestion.in') }} '+data[0].data[index].product.categories[0].name+'</p>'+
                                                            '</div></a>'
                                                        );
                                                    }
                                                } else {
                                                    $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                        'href="'+data[0].data[index].url_key+'"> <div class="velocity_category">'+
                                                        '<p>'+data[0].data[index].name+'</p>'+
                                                        '</div></a>'
                                                    );
                                                }
                                            }
                                        }
                                    }

                                    if(display_terms_toggle == 1) {         
                                        if(display == 'ar') {
                                            $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                'href="categorysearch?category=&term='+term+'"><div class="velocity_terms">'+
                                                '<p class="velocity_termsa">'+term+'</p>'+
                                                '<p class="ar_termsa">'+data[0].data.length+'</p>'+
                                                '</div></a>'
                                            );
                                        } else {
                                            $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                'href="categorysearch?category=&term='+term+'"><div class="velocity_terms">'+
                                                '<p class="velocity_termsa">'+term+'</p>'+
                                                '<p class="ar_termsb">'+data[0].data.length+'</p>'+
                                                '</div></a>'
                                            );
                                        }
                                    }   

                                    if(display_product_toggle == 1) {
                                        $('.suggests').append('<div class="velocity_popular">'+
                                            '<p>{{ __('suggestion::app.shop.seach-suggestion.popular-products') }}</p>'+
                                            '</div>'
                                        );

                                        if (data[0].data.length < no_of_products) {
                                            for (let index = 0; index < data[0].data.length; index++) {  
                                                if(data[0].data[index].product.type == 'bundle') {

                                                    var mini_price = parseInt(data[0].data[index].min_price);
                                                    var minimum = mini_price.toFixed(2);

                                                    $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                        'href="'+data[0].data[index].url_key+'">'+
                                                        '<div class="velocity_product">'+
                                                        '<div class="velocity_img">'+
                                                        '<img style="'+
                                                        'width:100%;'+
                                                        '" src="'+data[2][index][0].small_image_url+'" >'+
                                                        '</div>'+
                                                        '<div class="imgp">'+
                                                        '<p class="velocity_img_name">'+data[0].data[index].name+'<br>{{ __('suggestion::app.shop.seach-suggestion.starting-from') }} $'+minimum+'</p>'+
                                                        '</div></div></a>'
                                                    );
                                                } else {    
                                                    $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                        'href="'+data[0].data[index].url_key+'">'+
                                                        '<div class="velocity_product">'+
                                                        '<div class="velocity_img">'+
                                                        '<img style="'+
                                                        'width:100%;'+
                                                        '" src="'+data[2][index][0].small_image_url+'" >'+
                                                        '</div>'+
                                                        '<div class="imgp">'+
                                                        '<p class="velocity_img_name">'+data[0].data[index].name+'<br>'+data[1][index]+'</p>'+
                                                        '</div></div></a>'
                                                    );
                                                }
                                            }
                                        } else if(data[0].data.length >= no_of_products) {
                                            for (let index = 0; index < no_of_products; index++) {         
                                                if(data[0].data[index].product.type == 'bundle') {

                                                    var mini_price = parseInt(data[0].data[index].min_price);
                                                    var minimum = mini_price.toFixed(2);  

                                                    $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                        'href="'+data[0].data[index].url_key+'">'+
                                                        '<div class="velocity_product">'+
                                                        '<div class="velocity_img">'+
                                                        '<img style="'+
                                                        'width:100%;'+
                                                        '" src="'+data[2][index][0].small_image_url+'" >'+
                                                        '</div>'+
                                                        '<div class="imgp">'+
                                                        '<p class="velocity_img_name">'+data[0].data[index].name+'<br>{{ __('suggestion::app.shop.seach-suggestion.starting-from') }} $'+minimum+'</p>'+
                                                        '</div></div></a>'
                                                    );
                                                } else {    
                                                    $('.suggests').append('<a style="color:black;text-decoration:none;"'+
                                                        'href="'+data[0].data[index].url_key+'">'+
                                                        '<div class="velocity_product">'+
                                                        '<div class="velocity_img">'+
                                                        '<img style="'+
                                                        'width:100%;'+
                                                        '" src="'+data[2][index][0].small_image_url+'" >'+
                                                        '</div>'+
                                                        '<div class="imgp">'+
                                                        '<p class="velocity_img_name">'+data[0].data[index].name+'<br>'+data[1][index]+'</p>'+
                                                        '</div></div></a>'
                                                    );
                                                }
                                            }
                                        }
                                    }

                                    var search = event.target.value;
                                    var n = search.length;

                                    if(n >= 2) {
                                        var str = document.getElementById("old-new").innerHTML;
                                        var search = event.target.value;
                                        var regex = new RegExp(search, 'g');
                                        var result = str.replace(regex, '<b>' + search + '</b>');
                                        console.log(result);
                                        document.getElementById("old-new").innerHTML = result;
                                    }
                                } else {
                                    $('.suggests').append('<div class="velocity_no_result">'+
                                        '<p>{{ __('suggestion::app.shop.seach-suggestion.no-results') }}</p>'+
                                        '</div>'
                                    );   
                                }                         
                            }
                        }
                    });  
                },

                focusInput: function(event) {
                    $(event.target.parentElement.parentElement)
                        .find('input')
                        .focus();
                },

                submitForm: function() {
                    if (this.inputVal !== '') {
                        $('input[name=term]').val(this.inputVal);
                        $('#search-form').submit();
                    }
                }
            }
        })
    })()
</script>

@endpush

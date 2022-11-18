@extends('admin::layouts.content')

@section('page_title')
    {{ __('googleFeed::app.admin.layouts.export') }}
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/googlefeed.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/csspin-round.css') }}">
@endsection
@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('googleFeed::app.admin.layouts.export') }}</h1>
            </div>
            <div class="page-action">

            </div>
        </div>

        <div class="page-content">
            <export-product />
        </div>
    </div>


@stop


@push('scripts')
    <script type="text/x-template" id="export-product-template">
        <div class="export-product">
            <div class="export-btn">
                <div class="btn btn-primary" @click="exportProduct()">
                    {{ __('googleFeed::app.admin.product.export') }}
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('export-product', {
            template: '#export-product-template',


            methods: {
                exportProduct: function () {

                    let thisthis = this;
                    const cnf =  confirm('It may take a while Please wait until all product exported to google shop')
                    if (cnf) {
                        thisthis.showLoader()
                        document.querySelector('.cp-spinner').classList.add('show-spinner')

                        axios.get("{{route('googleFeed.products.export')}}")
                            .then(function (response) {
                                console.log(response)
                                thisthis.hideLoader()
                                document.querySelector('.cp-spinner').classList.remove('show-spinner')
                                if (response.status == 200) {
                                    window.flashMessages = [{'type': 'alert-success', 'message': response.data}];
									console.log(response.data);
                                    thisthis.hideLoader()
                                    document.querySelector('.cp-spinner').classList.remove('show-spinner')
                                    thisthis.$root.addFlashMessages()
                                }
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);

                                if (error.response.status == 401) {
                                    window.flashMessages = [{'type': 'alert-warning', 'message': error.response.data}];

                                    thisthis.hideLoader()
                                    document.querySelector('.cp-spinner').classList.remove('show-spinner')
                                    thisthis.$root.addFlashMessages()


                                } else {

                                }
                                thisthis.hideLoader()
                                document.querySelector('.cp-spinner').classList.remove('show-spinner')
                            })
                        }
                    },

                     showLoader : function () {
                        $('#loader').show();
                        $('.overlay-loader').show();

                        document.body.classList.add("modal-open");
                    },

                    hideLoader:  function () {
                        $('#loader').hide();
                        $('.overlay-loader').hide();

                        document.body.classList.remove("modal-open");
                    }
                }


        });
    </script>

<div class="cp-spinner cp-round"></div>
@endpush


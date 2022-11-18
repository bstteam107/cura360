@extends('shop::layouts.master')

@section('page_title')
{{ __('contact_lang::app.shop.title') }}
@endsection

@section('content-wrapper')
<div class="auth-content form-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-sm-12 col-md-7">
                {{-- <div class="heading">
                    <h2 class="fs24 fw6">
                        {{ __('contact_lang::app.shop.title') }}
                </h2>
            </div> --}}

            <div class="body col-12">
                <h3 class="fw6">Get in touch with us</h3>

                <p class="fs16">
                    If you want to know something, just send us a message, we glad to hear from you.
                </p>

                <form class="cd-form floating-labels" action="{{ route('shop.contact.send-message') }}" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <label class="cd-label " for="cd-name">Name <span class="required"></span></label>
                                <input class="text-input form-style" type="text" name="name" id="cd-name" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <label class="cd-label" for="cd-email">Email <span class="required"></span></label>
                                <input class="text-input  form-style" type="email" name="email" id="cd-email" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <label class="cd-label" for="cd-mobile">Phone Number</label>
                                <input class="text-input  form-style" type="number" name="phone" id="cd-mobile" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <label class="cd-label" for="cd-inquiry">What is the nature of your inquiry? <span class="required"></span></label>
                                <select class="text-input form-style" name="inquiry" id="cd-inquiry" required>
                                    <option value="" selected disabled>Select</option>
                                    <option value="I am a customer">I am a customer</option>
                                    <option value="I am a seller">I am a seller</option>
                                    <option value="I am a distributor">I am a distributor</option>
                                    <option value="I am from the media">I am from the media</option>
                                    <option value="I want to discuss partnering">I want to discuss partnering</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="cd-label" for="cd-textarea">Message <span class="required"></span></label>
                        <textarea class="message  form-control" name="message_body" rows="5" id="cd-textarea" required></textarea>
                    </div>

                    <div>
                        <button type="submit" class="theme-btn btn-block p-3"><i class="fa fa-paper-plane"></i> Send Message</button>
                    </div>

                </form>

            </div>
        </div>
        <div class=" col-lg-4 col-sm-12 col-md-5">
            <h3>Corporate Headquarters:</h3>
            <div class="flex-box">
                <div class="flex-box-icon"><i class="rango-location"></i></div>
                <div class="flex-box-text">2418 Crossroads Drive, Suite#1700,<br>Madison, WI, 53718</div>
            </div>

            <div class="flex-box">
                <div class="flex-box-icon"><span class="material-icons">local_phone</span></div>
                <div class="flex-box-text"><span>Office Phone :</span><br> <a href="tel:16089993336">1-608-999-3336</a></div>
            </div>

            <div class="flex-box">
                <div class="flex-box-icon"><span class="material-icons">headset_mic</span></div>
                <div class="flex-box-text"><span>Customer Service :</span><br> <a href="tel:18332073433">1-833-207-3433</a></div>
            </div>

            <div class="flex-box">
                <div class="flex-box-icon"><span class="material-icons">perm_phone_msg</span></div>
                <div class="flex-box-text"><span>Fax :</span><br> <a href="fax:6089993344">608-999-3344</a></div>
            </div>

            <div class="flex-box">
                <div class="flex-box-icon"><span class="material-icons">email</span></div>
                <div class="flex-box-text"><span>Email :</span><br> <a href="mailto:info@cura360.com">info@cura360.com</a></div>
            </div>

            <div class="flex-box">
                <div class="flex-box-icon"><span class="material-icons">contact_mail</span></div>
                <div class="flex-box-text"><span>Customer Service Email :</span><br> <a href="mailto:customerservice@cura360.com">customerservice@cura360.com</a></div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
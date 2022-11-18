@component('shop::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                @include ('shop::emails.layouts.logo')
            </a>
        </div>


        <div style="padding: 30px;">
            <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
                <p style="font-weight: bold;font-size: 20px;color: #242424;line-height: 24px;">
                    {{ __('shop::app.mail.customer.registration.dear', ['customer_name' => $data['NAME']]) }},
                </p>

                <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                    We have recieved your message will contact you soon.
                </p>
            </div>

            <div style="font-size: 16px;color: #5E5E5E;line-height: 30px;margin-bottom: 20px !important;">
                
            </div>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                Thanks for contacting us!!
            </p>
        </div>
    </div>

@endcomponent
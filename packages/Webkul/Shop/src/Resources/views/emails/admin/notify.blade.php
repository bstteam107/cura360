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
                    {{ __('shop::app.mail.customer.registration.dear-admin', ['admin_name' => core()->getAdminEmailDetails()['name']]) }},
                </p>

                <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                   You have one new notify mail by paytomorrow.
                </p>
            </div>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                OrderID: {{$data['order_id']}}<br>
				Status: {{$data['status']}}<br>
				Thanks!
            </p>
        </div>
    </div>

@endcomponent
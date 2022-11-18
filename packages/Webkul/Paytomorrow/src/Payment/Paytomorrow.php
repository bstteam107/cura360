<?php

namespace Webkul\Paytomorrow\Payment;

use Webkul\Payment\Payment\Payment;

class Paytomorrow extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paytomorrow';

    public function getRedirectUrl()
    {
       
		return route('paytomorrow.make.payment');
	   /*return 'https://consumer.paytomorrow.com/verify/personal?app=ed26e736-7511-48ad-975b-fc46e134c644&auth=031ba8e3-9b48-47d5-b1a8-e1422c7d6720';*/
    }
}
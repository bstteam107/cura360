<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Customer\Http\Requests\CustomerRegistrationRequest;
use Webkul\Customer\Mail\RegistrationEmail;
use Webkul\Customer\Mail\VerificationEmail;
use App\Mail\CallbackEmail;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Shop\Mail\SubscriptionEmail;
use Illuminate\Routing\Route;
class RegistrationController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customer
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param  \Webkul\Core\Repositories\SubscribersListRepository  $subscriptionRepository
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected SubscribersListRepository $subscriptionRepository
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Opens up the user's sign up form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @param  \Webkul\Customer\Http\Requests\CustomerRegistrationRequest  $request
     * @return \Illuminate\Http\Response
     */
	public function callback(Request $request)
    {
		//print_r($request->all());
		$data = array(
		 'name' => $request->input('NAME'),
		 'email' => $request->input('Email'),
		 'phone' => $request->input('COBJ2CF1'),
		 'city' => $request->input('COBJ2CF2'),
		 'message' => $request->input('COBJ2CF3'),
		 'date' => date('d-m-y h:i:s'),
		);
		$id = DB::table('callback')->insertGetId($data);
		if($id){
		Mail::queue(new CallbackEmail($request->all(), 'customer'));
		Mail::queue(new CallbackEmail($request->all(), 'admin'));
		 
		}
		//print_r($data);
		//echo 'testing';
		 echo 1;
		
	}
	public function subscribe(Request $request)
    {
		//echo 'dev';die();
        /*$this->validate($request, [
            'subscriber_email' => 'email|required',
        ]);*/

        $email = $request->input('subscriber_email');

        $unique = 0;

        $alreadySubscribed = $this->subscriptionRepository->findWhere(['email' => $email]);

        $unique = function () use ($alreadySubscribed) {
            return $alreadySubscribed->count() > 0 ? 0 : 1;
        };

        if ($unique()) {
            $token = uniqid();

            $subscriptionData['email'] = $email;
            $subscriptionData['token'] = $token;

            $mailSent = true;

            try {
                Mail::queue(new SubscriptionEmail($subscriptionData));

                session()->flash('success', trans('shop::app.subscription.subscribed'));
            } catch (\Exception $e) {
                report($e);
                session()->flash('error', trans('shop::app.subscription.not-subscribed'));

                $mailSent = false;
            }

            $result = false;

            if ($mailSent) {
                $result = $this->subscriptionRepository->create([
                    'email'         => $email,
                    'channel_id'    => core()->getCurrentChannel()->id,
                    'is_subscribed' => 1,
                    'token'         => $token,
                ]);

                if (! $result) {
                    session()->flash('error', trans('shop::app.subscription.not-subscribed'));

                    return redirect()->back();
                }
            }
        echo 1;
		} else {
            session()->flash('error', trans('shop::app.subscription.already'));
			echo 2;
        }

       
    }
    public function create(CustomerRegistrationRequest $request)
    {
        $request->validated();

        $data = array_merge(request()->input(), [
            'password'                  => bcrypt(request()->input('password')),
            'api_token'                 => Str::random(80),
            'is_verified'               => core()->getConfigData('customer.settings.email.verification') ? 0 : 1,
            'customer_group_id'         => $this->customerGroupRepository->findOneWhere(['code' => 'general'])->id,
            'token'                     => md5(uniqid(rand(), true)),
            'subscribed_to_news_letter' => isset(request()->input()['is_subscribed']) ? 1 : 0,
        ]);

        Event::dispatch('customer.registration.before');

        $customer = $this->customerRepository->create($data);

        Event::dispatch('customer.registration.after', $customer);

        if (! $customer) {
            session()->flash('error', trans('shop::app.customer.signup-form.failed'));

            return redirect()->back();
        }

        if (isset($data['is_subscribed'])) {
            $subscription = $this->subscriptionRepository->findOneWhere(['email' => $data['email']]);

            if ($subscription) {
                $this->subscriptionRepository->update([
                    'customer_id' => $customer->id,
                ], $subscription->id);
            } else {
                $this->subscriptionRepository->create([
                    'email'         => $data['email'],
                    'customer_id'   => $customer->id,
                    'channel_id'    => core()->getCurrentChannel()->id,
                    'is_subscribed' => 1,
                    'token'         => $token = uniqid(),
                ]);

                try {
                    Mail::queue(new SubscriptionEmail([
                        'email' => $data['email'],
                        'token' => $token,
                    ]));
                } catch (\Exception $e) {}
            }
        }

        if (core()->getConfigData('customer.settings.email.verification')) {
            try {
                if (core()->getConfigData('emails.general.notifications.emails.general.notifications.verification')) {
                    Mail::queue(new VerificationEmail(['email' => $data['email'], 'token' => $data['token']]));
                }

                session()->flash('success', trans('shop::app.customer.signup-form.success-verify'));
            } catch (\Exception $e) {
                report($e);

                session()->flash('info', trans('shop::app.customer.signup-form.success-verify-email-unsent'));
            }
		} else {
            try {
                if (core()->getConfigData('emails.general.notifications.emails.general.notifications.registration')) {
                    Mail::queue(new RegistrationEmail(request()->all(), 'customer'));
                }

                if (core()->getConfigData('emails.general.notifications.emails.general.notifications.customer-registration-confirmation-mail-to-admin')) {
                    Mail::queue(new RegistrationEmail(request()->all(), 'admin'));
                }

                session()->flash('success', trans('shop::app.customer.signup-form.success-verify'));
            } catch (\Exception $e) {
                report($e);

                session()->flash('info', trans('shop::app.customer.signup-form.success-verify-email-unsent'));
            }
            session()->flash('success', trans('shop::app.customer.signup-form.success'));
        }

        echo 1;
		
    }

    /**
     * Method to verify account.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount($token)
    {
        $customer = $this->customerRepository->findOneByField('token', $token);

        if ($customer) {
            $this->customerRepository->update(['is_verified' => 1, 'token' => 'NULL'], $customer->id);

            $this->customerRepository->syncNewRegisteredCustomerInformations($customer);

            session()->flash('success', trans('shop::app.customer.signup-form.verified'));
        } else {
            session()->flash('warning', trans('shop::app.customer.signup-form.verify-failed'));
        }

        return redirect()->route('customer.session.index');
    }

    /**
     * Resend verification email.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationEmail($email)
    {
        $verificationData = [
            'email' => $email,
            'token' => md5(uniqid(rand(), true)),
        ];

        $customer = $this->customerRepository->findOneByField('email', $email);

        $this->customerRepository->update(['token' => $verificationData['token']], $customer->id);

        try {
            Mail::queue(new VerificationEmail($verificationData));

            if (Cookie::has('enable-resend')) {
                \Cookie::queue(\Cookie::forget('enable-resend'));
            }

            if (Cookie::has('email-for-resend')) {
                \Cookie::queue(\Cookie::forget('email-for-resend'));
            }
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('shop::app.customer.signup-form.verification-not-sent'));

            return redirect()->back();
        }

        session()->flash('success', trans('shop::app.customer.signup-form.verification-sent'));

        return redirect()->back();
    }
}

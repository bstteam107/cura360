<?php

namespace Webkul\GoogleShoppingFeed\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\GoogleShoppingFeed\Helpers\GoogleShoppingContentApi;
use Webkul\GoogleShoppingFeed\Repositories\OAuthAccessTokenRepository;
use Carbon\Carbon;

class AccountController extends Controller
{

    /**
     * OAuthAccessTokenRepository repository object
     */
    protected $oAuthAccessTokenRepository;

    /**
     * Holds object of GoogleShoppingContentApi
     */
    protected $googleShoppingContentApi;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    public function __construct
    (
        GoogleShoppingContentApi $googleShoppingContentApi,
        OAuthAccessTokenRepository $oAuthAccessTokenRepository

    )
    {
        $this->_config = request('_config');
        $this->middleware('admin');
        $this->googleShoppingContentApi = $googleShoppingContentApi;
        $this->oAuthAccessTokenRepository = $oAuthAccessTokenRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $client = new \Google_Client();
            $client->setApplicationName('Sample Content API application');
            $client->setClientId(core()->getConfigData('googleFeed.settings.general.google_api_key'));
            $client->setClientSecret(core()->getConfigData('googleFeed.settings.general.google_api_secret_key'));
            $client->setAccessType("offline");
            $client->setRedirectUri(route('googleFeed.account.auth.redirect'));
            $client->setScopes('https://www.googleapis.com/auth/content');
            header('Location: ' . $client->createAuthUrl());
            exit;
        } catch (\Exception $e) {
            dd($e);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function redirect()
    {

        try {

            $client = new \Google_Client();
            $client->setApplicationName('Sample Content API application');
            $client->setClientId(core()->getConfigData('googleFeed.settings.general.google_api_key'));
            $client->setClientSecret(core()->getConfigData('googleFeed.settings.general.google_api_secret_key'));
            $client->setRedirectUri(route('googleFeed.account.auth.redirect'));
            $client->setAccessType("offline");
            $token = $client->authenticate(request()->code);

            // dd($token);
            $current = Carbon::now();
            $current->addSeconds($token['expires_in']);
            $oauthAccess = $this->oAuthAccessTokenRepository->first();

            // dd($oauthAccess);

            if (is_null($oauthAccess)) {
                $this->oAuthAccessTokenRepository->create([
                    'access_token' => $token['access_token'],
                    'expire_at'    => $current
                ]);
            } else {
                $oauthAccess->update([
                    'access_token' => $token['access_token'],
                    'expire_at'    => $current
                ]);
            }
        } catch (\Exception $e) {
            session()->flash('error' , $e);
        }

        session()->flash('success', __('googleFeed::app.admin.layouts.settings.auth-success'));

        return redirect()->route('googleFeed.account.auth');
    }

    /**
     * Refresh token after expire
     */
    public function refresh()
    {
        try {
            $client = new \Google_Client();
            $client->setApplicationName('Sample Content API application');
            $client->setClientId(core()->getConfigData('googleFeed.settings.general.google_api_key'));
            $client->setClientSecret(core()->getConfigData('googleFeed.settings.general.google_api_secret_key'));
            $client->setAccessType("offline");
            $client->setRedirectUri(route('googleFeed.account.auth.redirect'));
            $client->setScopes('https://www.googleapis.com/auth/content');
            header('Location: ' . $client->createAuthUrl());
            exit;
        } catch (\Exception $e) {
            session()->flash('error', $e);
        }

        session()->flash('success', __('googleFeed::app.admin.layouts.settings.refreshed-token'));

        return redirect()->back();

    }

}

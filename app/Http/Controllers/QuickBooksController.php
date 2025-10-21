<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Support\Facades\Session;

class QuickBooksController extends Controller
{
    protected function dataService()
    {
        return DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => env('QBO_CLIENT_ID'),
            'ClientSecret' => env('QBO_CLIENT_SECRET'),
            'RedirectURI' => env('QBO_REDIRECT_URI'),
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => env('QBO_ENV')
        ]);
    }

    // Step 1: Redirect user to QuickBooks login
    public function connect()
    {
        $dataService = $this->dataService();
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        return redirect()->away($authorizationUrl);
    }

    // Step 2: Handle callback and exchange code for tokens
    public function callback(Request $request)
    {
        $code = $request->code;
        $realmId = $request->realmId;

        $dataService = $this->dataService();
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);

        // Save tokens in session or DB
        Session::put('qbo_access_token', $accessTokenObj->getAccessToken());
        Session::put('qbo_refresh_token', $accessTokenObj->getRefreshToken());
        Session::put('qbo_realm_id', $realmId);

        return "QuickBooks connected! Access Token stored.";
    }
}

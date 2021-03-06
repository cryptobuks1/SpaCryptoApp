<?php

Route::group(['namespace' => 'Auth'], function() {

	Route::post('/register', 'SignUpController@register');
	Route::post('/login', 'SignInController@login');
	Route::get('/me', 'MeController@me');
	Route::post('/logout', 'SignOutController@logout');
	Route::post('/login/verify', 'TwoFactorLoginController@verify');
	Route::get('/login/resend', 'TwoFactorLoginController@getResend');

});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'admin'], 'namespace' => 'Admin'], function() {
	Route::get('/', 'ImpersonateController@index');
	Route::get('/impersonate', 'ImpersonateController@ipmersonate');
	Route::get('/getuser/{user}', 'ImpersonateController@getUser');
	Route::post('/transfers', 'TransactionController@index');
});

Route::get('/timeline', 'TimelineController@index');

/**
 * Account
 */
Route::group(['prefix' => 'account', 'middleware' => ['auth:api']], function() {
	Route::get('/', 'Account\AccountController@index');

	/**
	 * Profile
	 */
	Route::get('profile', 'Account\ProfileController@index');
	Route::post('profile', 'Account\ProfileController@store');
	/**
	 * Password
	 */
	Route::post('password', 'Account\PasswordController@store');

	/**
	 * Deactivate
	 */
	Route::post('deactivate', 'Account\DeactivateController@store');

	/**
	 * Twofactor
	 */
	Route::get('twofactor', 'Account\TwoFactorController@index');
	Route::post('twofactor', 'Account\TwoFactorController@store');
	Route::post('twofactor/verify', 'Account\TwoFactorController@verify');
	Route::delete('twofactor', 'Account\TwoFactorController@destroy');
	Route::get('twofactor/resend', 'Account\TwoFactorController@getResend');
});

/**
 * Portfolio
 */
Route::group(['prefix' => 'portfolio', 'middleware' => ['auth:api'], 'namespace' => 'Portfolio'], function() {
	Route::post('/', 'MakeWalletController@store');
	Route::get('getwallets', 'MakeWalletController@getWallets');
	Route::get('gettransfers/{wallet}', 'MakeWalletController@transactions');
	Route::post('sendtransfer/{wallet}', 'MakeWalletController@send');
	Route::post('address', 'MakeWalletController@makeAddress');
	
	Route::get('getcoins', 'CoinsController@index');
	Route::get('allwallets', 'WalletController@index');
	Route::get('unlock', 'MakeWalletController@unlock');
	Route::get('/{wallet}', 'MakeWalletController@getAWallet');
});

/**
 * Activation
 */
Route::group(['prefix' => 'activation', 'middleware' => ['guest']], function() {
    Route::post('/resend', 'Auth\ActivationResendController@store');
    Route::get('/{confirmation_token}', 'Auth\ActivationController@activate')->name('activation.activate');
});

/**
 * Password reset
 */
Route::group(['prefix' => 'password', 'middleware' => ['guest']], function() {
    Route::post('/resend', 'Auth\ForgotPasswordResendController@store');
    Route::post('/{confirmation_token}', 'Auth\ForgotPasswordResetController@reset')->name('password.reset');
});
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Resources\PrivateUserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class SignInController extends Controller
{
	use AuthenticatesUsers;

    protected $auth;

    public function __construct(JWTAuth $auth)
    {
    	$this->auth = $auth;
    }

    public function login(LoginFormRequest $request)
    {
    	try {
    		if (!$token = $this->auth->attempt($request->only('email', 'password'))) {
    			return response()->json([
    				'errors' => [
    					'root' => 'Could not sign you in with those credentials.'
    				]
    			], 401);
    		}
    		
    	} catch (JWTException $e) {
    		return response()->json([
    			'errors' => [
    				'root' => 'Failed.'
    			]
    		], $e->getStatusCode());
    	}

    	// check if is active

    	return (new PrivateUserResource($request->user()))->additional([
    		'meta' => [
    			'token' => $token
    		]
    	]);
    }
}
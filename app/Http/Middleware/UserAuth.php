<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->auth) {
            // Optional login, skip
            return $next($request);
        }

        // Pre-Middleware Action
        $customer = User::find($request->auth->sub);

        // customer exist ?
        if ($customer == null) {
            return response(['error_msg' => trans("messages.unauthorized_1012")], 401);
        }

        // customer active ?
        if (!$customer->active) {
            return response(['error_msg' => trans("messages.unauthorized_1013")], 401);
        }

        // Check for token
        $customerToken = UserToken::where(['user_id' => $customer->id, 'token_no' => $request->auth->no])->first();
        if ($customerToken == null) {
            return response(['error_msg' => trans("messages.session_not_exist")], 401);
        }
        if ($customerToken->isExpired()) {
            return response(['error_msg' => trans("messages.session_is_expired")], 401);
        }

        $request->token_no = $request->auth->no;
        // Customer Login
        Auth::login($customer);

        // Check the Customer Lang is same as Mobile app language
        // $locale = App::getLocale();
        // if ($locale !== null && $locale !== Auth::user()->lang) {
        //     $customer->lang = $locale;
        //     $customer->save();
        // }
        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}

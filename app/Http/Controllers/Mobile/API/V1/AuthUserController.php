<?php

namespace App\Http\Controllers\Mobile\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    /**
     * Check JWT Token
     *
     * @return boolean
     */
    public function isValid()
    {
        return response(['success' => true]);
    }
    /**
     * Customer Login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|max:100',
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [$validated['email']])->first();

        if ($user != null) {
            // Verify the password and generate the token
            if ($user->checkPassword($validated['password'])) {
                // Check user active or not

                if (!$user->active) {
                    return response(['username' => trans("err.User is disabled")]);
                }

                // Token generate
                return response([
                    'token' => $user->getJwt(),
                    'user_id' => $user->id,
                ]);
            }
        }
        return $this->fireErrorMobile("Incorrect E-mail Or Password");
    }

    /**
     * Customer Logout
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $token = UserToken::where([
            'user_id' => $request->auth->sub,
            'token_no' => $request->token_no,
        ])->firstOrFail();
        $token->delete();
        return response(["success" => true]);
    }

    /**
     * User Login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email|max:45',
            'password' => 'required|string|min:6',
        ]);
        // Save user
        $user = new User($validated);
        $user->save();

        return response([
            'token' => $user->getJwt(),
            'user_id' => $user->id,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{

    public function signin(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first()
            ], 400);
        }

        if(!Auth::attempt(request(['email', 'password']))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $result = $user->createToken('Personal Access Token');
        $token = $result->token;
        $token->save();

        return response()->json([
            'access_token' => $result->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function signup(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:50|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first()
            ], 400);
        }

       $user = User::create([
        'name' => $request->get('name'),
        'email' => $request->get('email'),
        'password' => bcrypt($request->get('password'))
        ]);
       $user->save();

      $account = Account::create([
            "agency" => random_int(100,999),
            "account_number" => random_int(100,999),
            "user_id" => $user->id
        ]);
        $account->save();

        return response()->json([
            'message' => 'Successfully!'
        ], 201);
    }
    public function signout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function profile() {
       $user = DB::table('users')
        ->join('accounts', 'users.id', '=', 'accounts.user_id')
        ->select('users.name', 'accounts.agency', 'accounts.account_number', 'accounts.balance')
        ->get();
        return response()->json($user[0]);
    }
}

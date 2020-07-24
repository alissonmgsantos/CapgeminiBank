<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agency' => 'required|integer',
            'account_number' => 'required|integer',
            'value' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        $user_account = Account::where('user_id', $request->user()->id)->first();
        $beneficiary_account = Account::where('agency', $request->get('agency'))
                        ->where('account_number', $request->get('account_number'))->first();

        if(!$beneficiary_account || $request->user()->id === $beneficiary_account->user_id ||
             $request->get('value') > $user_account->balance) {
            return response()->json(['message' => 'Operação inválida.'], 400);
        }

        Account::where('user_id', $request->user()->id)
        ->update(['balance' => $user_account->balance - $request->get('value')]);

        Account::where('user_id', $beneficiary_account->id)
        ->update(['balance' => $user_account->balance + $request->get('value')]);

        return response()->json([
            'message' => 'Successfully!'
        ], 201);
    }

    public function withDrawing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        $user = DB::table('accounts')->where('user_id', $request->user()->id)->get();


        if($request->get('value') > $user[0]->balance) {
            return response()->json(['message' => 'Operação inválida.'], 400);
        }

        DB::table('accounts')
        ->where('user_id', $user[0]->id)
        ->update(['balance' => $user[0]->balance - $request->get('value')]);


        return response()->json([
            'message' => 'Successfully!'
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\Mobile\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Get Wallet Balance
     *
     * @return void
     */
    public function getWalletBalance(){
        return response()->json([
            'balance' => Auth::user()->wallet_balance
        ]);
    }
    
    /**
     * Send Money To Another User
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function sendMoney(Request $request){
        $validated = $this->validateMobile($request ,[
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01',
        ],[
            'recipient_email.exists' => 'The recipient email does not match any existing user',
        ]);

        $sender = Auth::user();
        $recipient = User::where('email', $validated['recipient_email'])->first();
        $amount = $validated['amount'];

        // Can't send money to yourself
        if ($sender->id === $recipient->id) {
            return $this->fireErrorMobile('Cannot send money to yourself');
        }

        // check balance
        if ($sender->wallet_balance < $amount) {
            return $this->fireErrorMobile('Insufficient balance');
        }

        // Transaction Process
        DB::transaction(function () use ($sender, $recipient, $amount, &$transaction) {
            $sender->decrement('wallet_balance', $amount);
            $recipient->increment('wallet_balance', $amount);

            $transaction = Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'status' => 'completed',
            ]);

            // Sending Email Notification To Recipient
            event(new \App\Events\MoneySent($transaction));
        });

        return response()->json([
            'transaction_id' => $transaction->id,
            'sender_balance' => $sender->fresh()->wallet_balance,
            'recipient_balance' => $recipient->fresh()->wallet_balance,
        ]);
    }
}

<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MoneySent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Transaction $transaction;

    /**
     * Create a new event instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;

        // Get Sender & Recipient
        $sender = $transaction->sender;
        $recipient = $transaction->recipient;

        // Save Log Inside Trasaction Logs
        Log::channel('custom_transaction_log')->info("**********************************************************************************************");
        Log::channel('custom_transaction_log')->info("Email: {$sender->email} Send Money To {$recipient->email} with Amount : {$transaction->amount}");
        Log::channel('custom_transaction_log')->info("**********************************************************************************************");

        // Mail::to($transaction->sender->email)->send(new MoneySentMail($transaction));
        // To Sender
        Mail::to($transaction->sender->email)->send(new \App\Mail\MoneySentMail($transaction));

        // To reciver
        Mail::to($transaction->recipient->email)->send(new \App\Mail\MoneyReceivedMail($transaction));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

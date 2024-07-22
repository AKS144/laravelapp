<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function getWalletBalance()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();
        return response()->json(['balance' => $wallet->balance]);
    }

    public function sendEmails(Request $request)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'required|email',
            'message' => 'required|string',
        ]);

        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        $emailCount = count($request->emails);
        $totalCost = $emailCount * 1.10;

        if ($wallet->balance < $totalCost) {
            return response()->json(['message' => 'Insufficient wallet balance.'], 400);
        }

        foreach ($request->emails as $email) {
            $details = [
                'message' => $request->message
            ];

            Mail::to($email)->send(new CustomEmail($details));
        }

        $wallet->balance -= $totalCost;
        $wallet->save();

        return response()->json(['message' => 'Emails sent successfully.', 'new_balance' => $wallet->balance]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = app('firebase.auth');
    }

    public function payment(Request $request)
    {
        $rules = [
            'amount' => 'required|numeric|min:1000',
        ];
        
        $messages = [
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be numerical.',
            'amount.min' => 'The amount may not be less than :min.',
        ];
        
        $request->validate($rules, $messages);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');

        $transactionDetails = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->amount,
            ),
            'customer_details' => array(
                'email' => $request->session()->get('user')->email,
            ),
            'custom_data' => array(
                'user_id' => $request->session()->get('user')->uid
            ),
            'finish_redirect_url' => 'https://proyek-fai-t4z6-azure.vercel.app/user/saldo', 

        );

        $snapToken = Snap::getSnapToken($transactionDetails);

        return redirect()->back()->with('snapToken', $snapToken);
    }


    public function callback(Request $request)
    {
        $notification = new Notification();
        $isValidSignature = $notification->isValidSignature();
        \Log::info('Midtrans Notification:', $request->all());

        if ($isValidSignature) {
            $transactionStatus = $notification->transaction_status;
            $customData = $notification->custom_data;
            $id = $customData['user_id'];

            if ($transactionStatus == 'capture') {

                $user = $this->auth->getUser($id);

                $currentSaldo = $user->customClaims['saldo'] ?? 0;
                $currentRole = $user->customClaims['role'] ?? 0;

                $newSaldo = $currentSaldo + $request->amount;

                $this->auth->setCustomUserClaims($id, ['role' => $currentRole,'saldo' => $newSaldo]);

                // $this->refreshLoggedIn($request);

            } elseif ($transactionStatus == 'settlement') {

            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {

            } elseif ($transactionStatus == 'expire') {

            } elseif ($transactionStatus == 'pending') {

            }

            return response('OK', 200);
        } else {
            \Log::error('Invalid Midtrans Notification Signature');

            return response('Bad Request', 400);
        }
        
    }
}

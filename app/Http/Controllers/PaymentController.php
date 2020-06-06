<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Shetabit\Payment\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Payment\Invoice;

class PaymentController extends Controller
{
    public function purchase()
    {
        // Create new invoice.
        $invoice = (new Invoice)->amount(3000);
        // Purchase and pay the given invoice.
        // You should use return statement to redirect user to the bank page.
        return Payment::purchase($invoice, function($driver, $transactionId) {
            Cache::put('transaction_id', $transactionId, now()->addMinutes(30));
            // Store transactionId in database as we need it to verify payment in the future.
        })->pay();
    }

    public function verify()
    {
        // You need to verify the payment to ensure the invoice has been paid successfully.
        // We use transaction id to verify payments
        // It is a good practice to add invoice amount as well.
        try {
            $transaction_id = Cache::get('transaction_id');
            $receipt = Payment::amount(3000)->transactionId($transaction_id)->verify();

            // You can show payment referenceId to the user.
            echo $receipt->getReferenceId();

        } catch (InvalidPaymentException $exception) {
            /**
            when payment is not verified, it will throw an exception.
            We can catch the exception to handle invalid payments.
            getMessage method, returns a suitable message that can be used in user interface.
             **/
            echo $exception->getMessage();
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class ZarinpalController extends Controller
{
    public function pay()
    {
        $merchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'; //Required
        $amount = 1000;
        $description = 'توضیحات تراکنش تستی'; // Required
        $email = 'UserEmail@Mail.Com'; // Optional
        $mobile = '09123456789'; // Optional
        $callbackURL = 'http://localhost:8000/verify'; // Required

        $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentRequest(
            [
                'MerchantID' => $merchantID,
                'Amount' => $amount,
                'Description' => $description,
                'Email' => $email,
                'Mobile' => $mobile,
                'CallbackURL' => $callbackURL,
            ]
        );

        //Redirect to URL You can do it also by creating a form
        if ($result->Status == 100) {
            return \Illuminate\Support\Facades\Redirect::to('https://sandbox.zarinpal.com/pg/StartPay/'.$result->Authority);
        } else {
            echo'ERR: '.$result->Status;
        }
    }

    public function verify()
    {
        $MerchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
        $Amount = 1000; //Amount will be based on Toman
        $Authority = $_GET['Authority'];

        if ($_GET['Status'] == 'OK') {

            $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => $MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $Amount,
                ]
            );

            if ($result->Status == 100) {
                echo 'Transaction success. RefID:' . $result->RefID;
            } else {
                echo 'Transaction failed. Status:' . $result->Status;
            }
        } else {
            echo 'Transaction canceled by user';
        }
    }
}

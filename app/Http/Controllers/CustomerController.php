<?php

namespace App\Http\Controllers;

use App\Customer;

use App\Http\Requests;
use App\Http\Requests\StoreCardRequest;

use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api;
use PayPal\Api\CreditCard;

class CustomerController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function addCustomer(StoreCardRequest $request)
    {
        $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    env('CLIENT_ID'),     // ClientID
                    env('CLIENT_SECRET')      // ClientSecret
                )
            );

        $card = new CreditCard();

        //Set card information for PayPal
        $card->setType($request->input('creditcardtype'))
            ->setNumber($request->input('creditcardnumber'))
            ->setExpireMonth($request->input('expiremonth'))
            ->setExpireYear($request->input('expireyear'))
            ->setCvv2($request->input('cvv2'));

        //Set Merchand Id - this is NOT related to the Merchant Id in PayPal
        $card->setMerchantId(env('MERCH_ID'));

        //Set External Card Id - a unique identifier

        $external_card_id_raw = $request->input('creditcardholder');
        $external_card_id = sha1($external_card_id_raw);
        $card->setExternalCardId($external_card_id);

        $customer = Customer::create([
            'name'=>$request->input('creditcardholder'),
            'external_card_id' => $external_card_id
        ]);

        //Set External Customer Id - a unique identifier
        $card->setExternalCustomerId($customer->id);

        try {
            $card->create($apiContext);
        }
        catch (PayPalConnectionException $exception) {
            $customer->delete();
            $data = json_decode($exception->getData());
            if ($data->name == "DUPLICATE_MERCHANT_ID_EXTERNAL_CARD_ID")
                return redirect('/')->withErrors(['Card already added. Debug id: '.$data->debug_id]);
            return redirect('/')->withErrors(['Error authenticating the credit card']);
        }

        $customer->card_id = $card->id;
        $customer->save();

        return redirect('/')->with(['success'=>true]);
    }
}

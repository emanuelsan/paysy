<?php

namespace App\Http\Controllers;

use App\Customer;

use App\Http\Requests;
use App\Http\Requests\SelectCardRequest;
use App\Http\Requests\ConfirmCardRequest;
use App\Http\Requests\WithdrawRequest;

use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;

class PaymentController extends Controller
{
    public function select()
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                env('CLIENT_ID'),     // ClientID
                env('CLIENT_SECRET')      // ClientSecret
            )
        );

        $customers = Customer::all();

        $cards = [];

        $errors = [];

        foreach ($customers as $customer)
        {
            try {
                $card = CreditCard::get($customer->card_id, $apiContext);
            }
            catch (\Exception $exception) {
                array_push($errors, $exception);
            }
            array_push($cards, $card);

        }

        $customers = $customers->filter(function(Customer $item) use ($cards){
            foreach ($cards as $card)
            {
                if ($card->external_card_id == $item->external_card_id)
                    return true;
            }
            return false;
        });

        return view('select')->with([
            'cards' => $cards,
            'errors' => $errors,
            'customers' => $customers
        ]);
    }

    public function selectcard(SelectCardRequest $request)
    {
        $customer = Customer::whereCardId($request->input('card_id'))->first();

        if (!$customer)
            return abort(404);

        return view('withdraw')->with([
            'customer' => $customer
        ]);
    }

    public function confirm(ConfirmCardRequest $request)
    {
        $customer = Customer::whereCardId($request->input('card_id'))->first();

        if (!$customer)
            return abort(404);

        return view('confirm')->with([
            'customer' => $customer,
            'amount' => $request->input('amount')
        ]);
    }

    public function withdraw(WithdrawRequest $request)
    {
        $amountSum = $request->input('amount');
        $cardId = $request->input('card_id');

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                env('CLIENT_ID'),     // ClientID
                env('CLIENT_SECRET')      // ClientSecret
            )
        );

//        $apiContext->setConfig([
//            'mode' => 'live'
//        ]);

        $creditCardToken = new CreditCardToken();
        $creditCardToken->setCreditCardId($cardId);

        $fi = new FundingInstrument(); $fi->setCreditCardToken($creditCardToken);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")->setFundingInstruments(array($fi));

        $service = new Item();
        $service->setName('Web Development Services')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($amountSum);

        $itemList = new ItemList();
        $itemList->setItems(array($service));

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($amountSum);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Web Development Services to Websy via Paysy")
            ->setInvoiceNumber(uniqid());

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
        } catch (PayPalConnectionException $exception) {
            $data = json_decode($exception->getData());
            return redirect()->route('select')->withErrors([$data->message,$data->details]);
        }

        if ($payment->state == "approved")
        {
            \App\Payment::create([
                'amount' => $amountSum,
                'id' => $payment->id,
                'card_id' => $cardId,
                'invoice_number' => $payment->transactions[0]->invoice_number
            ]);
            return redirect()->route('select')->with(['success'=>$payment->id]);
        }
        else {
            return redirect()->route('select')->withErrors(['Payment not approved']);
        }
    }
}

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

use Hashids;

use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function customerPaymentPage(Request $request, $customer)
    {
        $customer = Customer::findOrFail(Hashids::decode($customer));
        if ($customer->isEmpty())
            abort(404);
        if (is_a($customer, "Illuminate\Database\Eloquent\Collection")) {
            $customer = $customer->first();
        }
        return view('customer_payment', ['customer' => $customer]);
    }

    public function customerPayment(Request $request, $customer)
    {
        $customer = Customer::findOrFail(Hashids::decode($customer));
        if ($customer->isEmpty())
            abort(404);
        if (is_a($customer, "Illuminate\Database\Eloquent\Collection")) {
            $customer = $customer->first();
        }

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                env('CLIENT_ID'),     // ClientID
                env('CLIENT_SECRET')      // ClientSecret
            )
        );

	    if (env('PAYPAL_LIVE') && strtolower(env('PAYPAL_LIVE')) == "live") {
		    $apiContext->setConfig([
			    'mode' => 'live'
		    ]);
	    }

        $card = new CreditCard();

        $parts = explode(" ", $request->input('creditcardholder'));
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);

        $card->setType($request->input('creditcardtype'))
            ->setNumber($request->input('creditcardnumber'))
            ->setExpireMonth($request->input('expiremonth'))
            ->setExpireYear($request->input('expireyear'))
            ->setCvv2($request->input('cvv2'))
            ->setFirstName($firstname)
            ->setLastName($lastname);

        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        $items = [];
        $total = 0;
        foreach($customer->billables as $billable)
        {
            $item = new Item();
            $item->setName($billable->name);
            $item->setCurrency(env('CURRENCY'));
            $item->setQuantity($billable->quantity);
            $item->setTax(0.0);
            $item->setPrice($billable->price);
            $total += $billable->price * $billable->quantity;
            $items[] = $item;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $amount = new Amount();
        $amount->setCurrency(env('CURRENCY'))
            ->setTotal($total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Stay at Riviera Apartments")
            ->setInvoiceNumber(uniqid());

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
        } catch (PayPalConnectionException $exception) {
	        debug($exception);
	        debug($exception->getCode());
	        debug($exception->getData());
	        return redirect()->route('customerPaymentPage',['customer'=>$customer->hash_id])->withErrors(['PayPal Error Ocurred']);
        }
        catch (Exception $exception) {
	        $data = json_decode($exception->getData());
	        debug($exception);
	        return redirect()->route('customerPaymentPage',['customer'=>$customer->hash_id])->withErrors([$data->message,$data->details]);
        }
        dd($payment);

        if ($payment->state == "approved")
        {
            \App\Payment::create([
                'amount' => $payment->transactions[0]->related_resources[0]->sale->amount->total,
                'customer_id' => $customer->id,
                'sale_id' => $payment->id,
                'invoice_id' => $payment->transactions[0]->invoice_number
            ]);

            $customer->delete();

            return view('thankyou',['invoice_id'=>$payment->transactions[0]->invoice_number]);
        }
        else {
            return redirect()->route('customerPaymentPage',['customer'=>$customer->hash_id])->withErrors(['Payment not approved']);
        }
    }

}

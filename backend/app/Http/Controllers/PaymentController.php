<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Response;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($query)
    {

        $total = Test::where('link',$query)->first()->total;

        $order_currency = Test::where('link',$query)->first()->service;

        $options = new \Iyzipay\Options();
        $options->setApiKey("krD90pQJHpniLuvL9aRlIHPFNflg1rrB");
        $options->setSecretKey("bVUIHXA1b36cPi8HTmhE4Fw3ofsRi4H3");
        $options->setBaseUrl("https://api.iyzipay.com");

        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId("123456789");
        $request->setPrice("1");
        $request->setPaidPrice("$total");
        if($order_currency =="PCT Entry"){
            $request->setCurrency(\Iyzipay\Model\Currency::USD);
        }
        else{
            $request->setCurrency(\Iyzipay\Model\Currency::EUR);
        }
        $request->setBasketId("B67832");
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('iyzico.callback',$query));
        $request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId("BY789");
        $buyer->setName("John");
        $buyer->setSurname("Doe");
        $buyer->setGsmNumber("+905350000000");
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setLastLoginDate("2015-10-05 12:43:35");
        $buyer->setRegistrationDate("2013-04-21 15:12:09");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setIp("85.34.78.112");
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName("Jane Doe");
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $shippingAddress->setZipCode("34742");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName("Jane Doe");
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $billingAddress->setZipCode("34742");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI101");
        $firstBasketItem->setName("Binocular");
        $firstBasketItem->setCategory1("Collectibles");
        $firstBasketItem->setCategory2("Accessories");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $firstBasketItem->setPrice("0.3");
        $basketItems[0] = $firstBasketItem;

        $secondBasketItem = new \Iyzipay\Model\BasketItem();
        $secondBasketItem->setId("BI102");
        $secondBasketItem->setName("Game code");
        $secondBasketItem->setCategory1("Game");
        $secondBasketItem->setCategory2("Online Game Items");
        $secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $secondBasketItem->setPrice("0.5");
        $basketItems[1] = $secondBasketItem;

        $thirdBasketItem = new \Iyzipay\Model\BasketItem();
        $thirdBasketItem->setId("BI103");
        $thirdBasketItem->setName("Usb");
        $thirdBasketItem->setCategory1("Electronics");
        $thirdBasketItem->setCategory2("Usb / Cable");
        $thirdBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $thirdBasketItem->setPrice("0.2");
        $basketItems[2] = $thirdBasketItem;
        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
        $form = $checkoutFormInitialize->getCheckoutFormContent();

        $token = $checkoutFormInitialize->getToken();
        DB::table('orders')->where('link',$query)->update(['token' => $token]);

        $order_status = Test::where('link',$query)->first();

        if(env('payment_under_construction')){
            return view('payment-under-construction');
        }
        else{
            if($order_status->order_status == "success")
            {
                return view('successful-payment');
            }
            else
            {
                return view('iyzico-form',compact("form"));
            }
        }
    }

    public function callback($query){

        $token = Test::where('link',$query)->first()->token;

        $orderinfo = Test::where('link',$query)->get()->toArray();

        $order = Test::where('link',$query)->first();

        $params = array(
            'name' => $order->user_name,
            'email' => $order->user_email,
            'phone' => $order->user_phone,
            'link' => $order->link,
            'service' => $order->service,
            'service_fee' => $order->service_fee,
            'official_fee' => $order->official_fee,
            'translation_quantity' => $order->translation_quantity,
            'translation_fee' => $order->translation_fee,
            'total' => $order->total,

        );

        $options = new \Iyzipay\Options();
        $options->setApiKey("krD90pQJHpniLuvL9aRlIHPFNflg1rrB");
        $options->setSecretKey("bVUIHXA1b36cPi8HTmhE4Fw3ofsRi4H3");
        $options->setBaseUrl("https://api.iyzipay.com");

        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456789");
        $request->setToken("$token");
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $options);

        if($checkoutForm->getPaymentStatus() == "SUCCESS") {
            DB::table('orders')->where('link',$query)->update(['order_status' => "success"]);

            Mail::send('mails.SuccessfulOrder',$params, function($message) use ($orderinfo)
            {
                $message->to($orderinfo[0]['user_email'],$orderinfo[0]['user_name'])->bcc('info@marka.legal','Marka.Legal')->subject('Payment successful (#'.$orderinfo[0]['link'].')');
                $message->from("info@patent.istanbul", "Patent.Istanbul");
            });

            return view('successful-payment');
        }
    }
}

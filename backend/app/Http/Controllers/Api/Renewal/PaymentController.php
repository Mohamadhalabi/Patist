<?php

namespace App\Http\Controllers\Api\Renewal;

use App\Http\Controllers\Controller;
use App\Mail\Invoice;
use App\Models\Renewal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Workflow\WorkflowStub;

class PaymentController extends Controller
{
    public function paymentOne(Request $request)
    {
        $renewal_id = $request->renewal_id;
        $renewal = Renewal::find($renewal_id);

        $options = new \Iyzipay\Options();
        $options->setApiKey("sandbox-AJmOd6d3e6a8PutHw20Eln7Ul4Jd6D8W");
        $options->setSecretKey("sandbox-zXKlRCrYqSVUnWEdKkDFjWcx5TRvREXs");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456789");
        $request->setPrice(1);
        $request->setPaidPrice($renewal->total_amount_eur);
        $request->setCurrency(\Iyzipay\Model\Currency::EUR);
        $request->setBasketId("B67832");
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('iyzico.callback', $renewal));
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
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setId("BI101");
        $basketItem->setName("Binocular");
        $basketItem->setCategory1("Collectibles");
        $basketItem->setCategory2("Accessories");
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $basketItem->setPrice("1");
        $basketItems[0] = $basketItem;
        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
        $form = $checkoutFormInitialize->getCheckoutFormContent();
        $paymentPageUrl = $checkoutFormInitialize->getPaymentPageUrl();
        $token = $checkoutFormInitialize->getToken();


        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $form,
            'paymentPageUrl' => $paymentPageUrl,
        ], 200);
    }

    public function paymentAll(Request $request)
    {
        $renewals = Renewal::where('status', 'payment-pending')->get();
        $user_id = $request->user_id ?? 1;
        $total_amount_eur = 0;
        foreach ($renewals as $renewal) {
            $total_amount_eur += $renewal->total_amount_eur;
        }


        $options = new \Iyzipay\Options();
        $options->setApiKey("sandbox-AJmOd6d3e6a8PutHw20Eln7Ul4Jd6D8W");
        $options->setSecretKey("sandbox-zXKlRCrYqSVUnWEdKkDFjWcx5TRvREXs");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456789");
        $request->setPrice(1);
        $request->setPaidPrice($total_amount_eur);
        $request->setCurrency(\Iyzipay\Model\Currency::EUR);
        $request->setBasketId("B67832");
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('iyzico.callback', 'UID_' . $user_id));
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
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setId("BI101");
        $basketItem->setName("Binocular");
        $basketItem->setCategory1("Collectibles");
        $basketItem->setCategory2("Accessories");
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $basketItem->setPrice("1");
        $basketItems[0] = $basketItem;
        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
        $form = $checkoutFormInitialize->getCheckoutFormContent();
        $paymentPageUrl = $checkoutFormInitialize->getPaymentPageUrl();
        $token = $checkoutFormInitialize->getToken();

        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $form,
            'paymentPageUrl' => $paymentPageUrl,
        ], 200);
    }

    public function callback($query)
    {
        // eğer $query UID_ ile başlıyorsa type=all başlamıyorsa type=one
        if (strpos($query, 'UID_') === false) {
            $renewal = Renewal::find($query);
            $renewal->status = 'pending';
            $renewal->save();

            Mail::to($renewal->user->email)->send(new Invoice($renewal));

            if($renewal->is_approved == true)
            {
                $workflow = WorkflowStub::load($renewal->workflow_id);
                $workflow->setStatus('RNWo.2.2', true);
            }

            $applications[] = $renewal;
            return view('renewals.callback', compact('applications'));
        }
        else{
            $user = User::find($query);
            $renewals = Renewal::where('status', 'payment-pending')->get();
            foreach ($renewals as $renewal) {
                $renewal->status = 'pending';
                $renewal->save();

                Mail::to($renewal->user->email)->send(new Invoice($renewal));

                if($renewal->is_approved == true)
                {
                    $workflow = WorkflowStub::load($renewal->workflow_id);
                    $workflow->setStatus('RNWo.2.2', true);
                }

                $applications[] = $renewal;
            }
            return view('renewals.callback', compact('applications'));
        }
    }
}

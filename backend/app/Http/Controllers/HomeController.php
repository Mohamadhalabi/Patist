<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\FaqController;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function orders()
    {
        $orders = Order::all();
        return view('dashboard.orders.orders',compact('orders'));
    }
    public function edit($id)
    {
        $edit = Order::where('id',$id)->first();

        return view('dashboard.orders.edit',compact('edit'));
    }
    public function paymentStatus($id)
    {
        $paymentstatus = Order::where('id',$id)->first();

        if($paymentstatus->order_status === 'success'){
            Order::where('id',$id)
                ->update([
                    'order_status' => 'Pending Payment'
                ]);
        }
        else{
            Order::where('id',$id)
                ->update([
                    'order_status' => 'success'
                ]);
        }

        return redirect()->back();
    }

    public function feedback()
    {
        $feedback = Feedback::all();
        return view('dashboard.feedback',compact('feedback'));
    }
    public function faqs()
    {

    }
    public function contact()
    {
        $contact = Contact::all();
        return view('dashboard.contact',compact('contact'));
    }
}

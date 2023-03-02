<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $data = [
            'intent' => auth()->user()->createSetupIntent()
        ];

        return view('subscriptions.payment')->with($data);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);
        
        $request->user()->newSubscription('default', 'price_1IWmwFGJDz1oPimHukgcaXhe')->create($request->token);

        return back();
    }
}

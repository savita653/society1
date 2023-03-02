<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $checkouts['science'] = auth()->user()->newSubscription('default', 'price_1IWmwFGJDz1oPimHukgcaXhe')
            ->trialDays(3)
            ->allowPromotionCodes()
            ->checkout();
        $checkouts['professor'] = auth()->user()->newSubscription('default', 'price_1IWmwFGJDz1oPimHukgcaXhe')
            ->allowPromotionCodes()
            ->checkout();
        $checkouts['student'] = auth()->user()->newSubscription('default', 'price_1IWmwFGJDz1oPimHukgcaXhe')
            ->allowPromotionCodes()
            ->checkout();
        $plans = config('plan');
        return view('subscriptions.plans', compact('plans', 'checkouts'));
    }
}

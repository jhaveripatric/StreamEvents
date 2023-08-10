<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follower;
use App\Models\Subscriber;
use App\Models\Donation;
use App\Models\MerchSale;

class EventController extends Controller
{
    public function index()
    {
        $events = [];

        $followers = Follower::orderBy('created_at', 'desc')->take(100)->get();
        foreach ($followers as $follower) {
            $events[] = $follower->name . ' followed you!';
        }

        $subscribers = Subscriber::orderBy('created_at', 'desc')->take(100)->get();
        foreach ($subscribers as $subscriber) {
            $events[] = $subscriber->name . ' (Tier' . $subscriber->subscription_tier . ') subscribed to you!';
        }

        $donations = Donation::orderBy('created_at', 'desc')->take(100)->get();
        foreach ($donations as $donation) {
            $events[] = $donation->name . ' donated ' . $donation->amount . ' ' . $donation->currency . ' to you!';
        }

        $merchSales = MerchSale::orderBy('created_at', 'desc')->take(100)->get();
        foreach ($merchSales as $sale) {
            $events[] = $sale->name . ' bought ' . $sale->item_name . ' from you for ' . $sale->amount . ' ' . $sale->price . '!';
        }

        return response()->json($events);
    }
}

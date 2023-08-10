<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follower;
use App\Models\Subscriber;
use App\Models\Donation;
use App\Models\MerchSale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = [];
        $followers = Follower::orderBy('created_at', 'desc')->get();
        foreach ($followers as $follower) {
            $events[] = ['id'=>'follower-'.$follower->id,'message'=>$follower->name . ' followed you!', 'read'=>$follower->read];
        }

        $subscribers = Subscriber::orderBy('created_at', 'desc')->get();
        foreach ($subscribers as $subscriber) {
            $events[] = ['id'=>'subscriber-'.$subscriber->id,'message'=>$subscriber->name . ' (Tier' . $subscriber->subscription_tier . ') subscribed to you!', 'read'=>$subscriber->read];
        }

        $donations = Donation::orderBy('created_at', 'desc')->get();
        foreach ($donations as $donation) {
            $events[] = ['id'=>'donation-'.$donation->id,'message'=>$donation->name . ' donated ' . $donation->amount . ' ' . $donation->currency . ' to you!', 'read'=>$donation->read];
        }

        $merchSales = MerchSale::orderBy('created_at', 'desc')->get();
        foreach ($merchSales as $sale) {
            $events[] = ['id'=>'sale-'.$sale->id,'message'=>$sale->name . ' bought ' . $sale->item_name . ' from you for ' . $sale->amount . ' ' . $sale->price . '!', 'read'=>$sale->read];
        }

        $page = $request->get('page', 1);
        $perPage = 100;
        $offset = ($page - 1) * $perPage;
        $paginatedEvents = array_slice($events, $offset, $perPage);
        $totalEvents = count($events);
        $totalPages = ceil($totalEvents / $perPage);

        $responseArray = [
            'events' => $paginatedEvents,
            'totalPages' => $totalPages,
        ];
        return response()->json($responseArray);
    }

    public function markRead(Request $request)
    {
        $eventId = explode('-', $request->get('eventId'))[1];
        $eventType = explode('-', $request->get('eventId'))[0];
        if($eventType === "follower"){
            $updated = Follower::where('id', $eventId)->update(['read' => DB::raw('NOT `read`')]);
            $updatedReadValue = Follower::where('id', $eventId)->value('read');
        }
        if($eventType === "subscriber"){
            $updated = Subscriber::where('id', $eventId)->update(['read' => DB::raw('NOT `read`')]);
            $updatedReadValue = Subscriber::where('id', $eventId)->value('read');
        }
        if($eventType === "donation"){
            $updated = Donation::where('id', $eventId)->update(['read' => DB::raw('NOT `read`')]);
            $updatedReadValue = Donation::where('id', $eventId)->value('read');
        }
        if($eventType === "sale"){
            $updated = MerchSale::where('id', $eventId)->update(['read' => DB::raw('NOT `read`')]);
            $updatedReadValue = MerchSale::where('id', $eventId)->value('read');
        }
        $responseArray = [
            'updated' => $updatedReadValue,
            'message' => 'Event marked as read/unread.'
        ];
        return response()->json($responseArray);
    }

    public function aggregation()
    {
        $startDate = Carbon::now()->subDays(30);
        $donationRevenue = Donation::where('created_at', '>=', $startDate)->sum('amount');

        $tier1Subscriptions = Subscriber::where('created_at', '>=', $startDate)->where('subscription_tier', 'tier1')->count();
        $tier2Subscriptions = Subscriber::where('created_at', '>=', $startDate)->where('subscription_tier', 'tier2')->count();
        $tier3Subscriptions = Subscriber::where('created_at', '>=', $startDate)->where('subscription_tier', 'tier3')->count();
        $subscriptionRevenue = ($tier1Subscriptions * 5) + ($tier2Subscriptions * 10) + ($tier3Subscriptions * 15);
        $merchRevenue = MerchSale::where('created_at', '>=', $startDate)->sum(DB::raw('amount * price'));

        $totalRevenue = $donationRevenue + $subscriptionRevenue + $merchRevenue;

        $followersGained = Follower::where('created_at', '>=', $startDate)->count();

        $topItems = MerchSale::where('created_at', '>=', $startDate)
            ->groupBy('item_name')
            ->selectRaw('item_name, SUM(amount*price) as total_sales')
            ->orderByDesc('total_sales')
            ->take(3)
            ->get();

        $responseArray = [
            'totalRevenue'=> $totalRevenue,
            'followersGained' => $followersGained,
            'topItems' => $topItems,
        ];
        return response()->json($responseArray);
    }
}


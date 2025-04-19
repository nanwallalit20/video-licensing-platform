<?php

namespace App\Http\Controllers;

use App\Enums\BuyerStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Mail\SubscriptionMail;
use App\Models\Order;
use App\Models\OrderMeta;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Helpers\Helpers;
use App\Enums\Roles;
use App\Models\Buyer;
use App\Models\UserProfile;
use App\Models\MapBuyerTitle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index() {
        return view('backend.dashboard');
    }

    public function buyerlist(Request $request) {
        $query = UserProfile::where('role_id', Roles::Buyer->value);

        // Apply name filter
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->name}%");
            });
        }

        // Apply email filter
        if ($request->filled('email')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }

        // Apply subscription filter
        if ($request->filled('subscription')) {
            $query->where('is_subscribed', $request->subscription);
        }

        // Get paginated results
        $buyers = $query->orderBy('id', 'desc')->paginate(10);

        return view('backend.buyerlist', compact('buyers'));
    }

    public function sellerlist(Request $request) {
        $query = UserProfile::where('role_id', Roles::Seller->value);

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        $sellers = $query->orderBy('id', 'desc')->paginate(10);
        return view('backend.sellerlist', compact('sellers'));
    }

    public function buyerRequests(Request $request) {
        $query = Buyer::query()->orderBy('created_at', 'desc');

        if ($request->filled('name')) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $buyers = $query->paginate(10);

        return view('backend.buyers.requests', compact('buyers'));
    }

    public function viewBuyerRequests($id) {
        $buyer = Buyer::findOrFail($id);
        $genres = $buyer->getGenres()->pluck('name')->toArray();

        return view('backend.buyers.viewDetails', compact(['genres', 'buyer']));
    }

    public function buyerRequestsStatus($buyer_id, $status) {
        $buyer = Buyer::findOrFail($buyer_id);
        if (!$buyer) {
            return response()->json([
                'status' => false,
                'message' => 'Buyer not found',
            ]);
        }
        if ($status == BuyerStatus::Rejected->value) {
            DB::table('buyers')
                ->where('id', $buyer->id)
                ->update(['status' => $status]);
            return response()->json([
                'status' => true,
                'message' => 'Buyer request declined successfully',
                'function' => 'pageReload'
            ]);
        } else {
            $tempPassword = Str::random(8);
            $user = User::create([
                'name' => $buyer->full_name,
                'email' => $buyer->email,
                'password' => Hash::make($tempPassword),
            ]);

            $mobile = $buyer->phone_number;
            $whatsapp = $buyer->whatsapp_number;
            $mobileParts = explode('-', $mobile);
            $mobile_cntry_code = ltrim($mobileParts[0], '+');
            $phone_number = $mobileParts[1];
            $whatsappParts = explode('-', $whatsapp);
            $whatsapp_cntry_code = ltrim($whatsappParts[0], '+');
            $whatsapp_number = $whatsappParts[1];
            $expiresAt = Carbon::now()->addHours(72);
            $token = Crypt::encryptString($user->id);
            $subscriptionLink = URL::temporarySignedRoute('buyer.subscription.pricing', $expiresAt, ['token' => $token]);
            UserProfile::create([
                'user_id' => $user->id,
                'role_id' => Roles::Buyer->value,
                'phone' => $phone_number,
                'country_code' => $mobile_cntry_code,
                'whatsapp_number' => $whatsapp_number,
                'Whatsapp_country_code' => $whatsapp_cntry_code,
                'is_subscribed' => SubscriptionStatus::NOT_SUBSCRIBED->value,
                'subscription_link' => $subscriptionLink,
                'link_expires_at' => $expiresAt,
            ]);

            DB::table('buyers')
                ->where('id', $buyer->id)
                ->update(['status' => $status]);

            Helpers::sendMail(new SubscriptionMail($buyer->full_name, $subscriptionLink), $buyer->email);
            return response()->json([
                'status' => true,
                'message' => 'Buyer request accepted successfully and a subscribe link has been sent to buyer.',
                'function' => 'pageReload'
            ]);
        }
    }

    public function analytics() {

        return view('backend.analytics');
    }

    public function transactions() {

        return view('backend.transactions');
    }

    public function settings() {

        return view('backend.settings');
    }

    public function titleRequests(Request $request) {

        $query = Order::query();

        // Apply filters
        if ($request->filled('name')) {
            $query->whereHas('getBuyer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->whereHas('getBuyer', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Paginate
        $orders = $query->orderBy('id', 'desc')->paginate(10);

        return view('backend.titles.titleRequests', [
            'orders' => $orders,
            'orderStatuses' => OrderStatus::cases(),
            'paymentStatuses' => PaymentStatus::cases()
        ]);
    }

    public function viewTitleRequests($id) {
        $order_details = OrderMeta::where('order_id', $id)->get();
        return view('backend.titles.viewDetails', compact('order_details'));
    }

    public function updateTitleRequests($orderId, $status) {
        $order = Order::findOrFail($orderId);
        if ($order) {
            $order->update([
                'order_status' => $status,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Title request status updated successfully',
                'function' => 'pageReload',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Title request not found',
            ]);
        }
    }

}

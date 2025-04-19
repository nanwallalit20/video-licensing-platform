<?php

namespace App\Http\Controllers;

use App\Enums\MediaTypes;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Roles;
use App\Enums\TitleType;
use App\Helpers\FileUploadHelper;
use App\Helpers\Helpers;
use App\Mail\AdminNotificationToSeller;
use App\Mail\TitleContentRequestToAdmin;
use App\Mail\TitleContentRequestToBuyer;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderMeta;
use App\Models\Season;
use App\Models\Title;
use Illuminate\Http\Request;
use App\Models\MapBuyerTitle;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class MapBuyerTitleController extends Controller {
    // Update selected titles
    public function updateSelectedTitles(Request $request) {
        $buyerId = Auth::id();
        $items = Cart::where('buyer_id', $buyerId)->get();
        // Validate items array
        if (empty($items)) {
            return response()->json([
                'message' => 'Please select at least one title to proceed.',
                'status' => 'error'
            ], 422);
        }

        // Create a new order
        $order_details = Order::create([
            'order_uuid' => Str::uuid(),
            'buyer_id' => $buyerId,
            'total_price' => 0,
            'currency' => 'USD',
            'order_status' => OrderStatus::Pending->value,
            'payment_status' => PaymentStatus::Pending->value
        ]);
        foreach ($items as $item) {
            $title = Title::find($item['title_id']);

            if (!$title) continue;

            if ($item['season_id']) {

                OrderMeta::create([
                    'order_id' => $order_details->id,
                    'title_id' => $title->id,
                    'season_id' => $item['season_id']
                ]);
            } else {
                OrderMeta::create([
                    'order_id' => $order_details->id,
                    'title_id' => $title->id,
                ]);
            }

        }
        Cart::where('buyer_id', $buyerId)->delete();

        $admin = User::whereHas('getUserProfile', function ($query) {
            $query->where('role_id', Roles::Superadmin);
        })->select('name', 'email')->first();


        $adminEmail = $admin->email;

        Helpers::sendMail(new TitleContentRequestToAdmin($order_details), $adminEmail);

        Helpers::sendMail(new TitleContentRequestToBuyer($order_details), Auth::user()->email);

        return response()->json([
            'status' => true,
            'message' => 'All title requests have been sent. We will get back to you soon with updates. Thank you for submitting the requests',
            'function' => 'pageReload'
        ]);
    }
}

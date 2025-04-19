<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\MediaTypes;
use App\Enums\TitleType;
use App\Models\Order;
use App\Models\OrderMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerContentController extends Controller
{
    public function myContent()
    {
        $user = Auth::user();
        $content = Order::with([
            'getOrderMetas.getTitle' => function($query) {
                $query->where(function($q) {
                    // For Movies
                    $q->where('type', TitleType::Movie->value)
                        ->with(['getTitleMediaMapping' => function($mediaQuery) {
                            $mediaQuery->where('media_type', MediaTypes::Image->value);
                        }]);
                })
                ->orWhere(function($q) {
                    // For Series
                    $q->where('type', TitleType::Series->value)
                        ->with(['getSeason.getMedia' => function($mediaQuery) {
                            $mediaQuery->where('file_type', MediaTypes::Image->value);
                        }]);
                });
            }
        ])
        ->where('buyer_id', $user->id)
        ->where('order_status', OrderStatus::Approved)
        ->orderBy('id', 'desc')
        ->get();
        return view('buyer.my-content', compact('content'));
    }

    public function downloadContent($id)
    {
        $orderMeta = OrderMeta::find($id);
        return view('buyer.content-download-link', compact('orderMeta'))->render();
    }
}

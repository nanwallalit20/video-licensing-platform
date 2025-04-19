<?php

namespace App\Http\Controllers;

use App\Enums\TitleType;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Title;
use App\Models\Season;

class CartController extends Controller {
    /**
     * Add or remove item from cart
     */
    public function toggleCartItem($titleId, $seasonId = null) {
        $buyerId = Auth::id();

        // Find and validate title
        $title = Title::find($titleId);
        if (!$title) {
            return response()->json([
                'status' => false,
                'message' => 'Title not found.',
            ], 404);
        }

        // Validate season if provided
        if ($seasonId) {
            $season = Season::where('id', $seasonId)
                ->where('title_id', $titleId)
                ->first();

            if (!$season) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid season for this title.',
                ], 404);
            }
        }

        // Check if item exists in cart
        $cartItem = Cart::where('buyer_id', $buyerId)
            ->where('title_id', $titleId)
            ->where('season_id', $seasonId)
            ->first();

        if ($cartItem) {
            // Remove item if it exists
            $cartItem->delete();
            $message = 'Title removed from cart.';
        } else {
            // Add new item
            Cart::create([
                'buyer_id' => $buyerId,
                'title_id' => $titleId,
                'season_id' => $seasonId,
            ]);
            $message = 'Title added to cart.';
        }

        // Get updated cart items
        $cartItems = Cart::where('buyer_id', $buyerId)
            ->select('title_id as titleId', 'season_id as seasonId')
            ->get();

        return response()->json([
            'status' => true,
            'message' => $message,
            'function' => 'updateCartItems',
            'data' => ['cartItems' => $cartItems]
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatus;
use App\Helpers\Helpers;
use App\Mail\BuyerCredentials;
use App\Mail\SubscriptionMail;
use App\Models\Buyer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class SubcribtionController extends Controller {
    public function buyerPricing($token) {
        try {
            // Decrypt token to get the user ID.
            $userId = Crypt::decryptString($token);
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['Invalid or expired link.']);
        }

        // Optionally, you can pass along user details and the token to the view.
        return view('pricing.content_library_access', compact('user'));
    }

    public function buyerCheckout($userId, $priceId) {

        $user = User::findOrFail($userId);
        if ($user) {
            return $user
                ->newSubscription(env('SUSCRIPTION_PRODUCT_ID'), $priceId)
                ->checkout([
                    'success_url' => route('stripe.success', ['userId' => $userId]),
                    'cancel_url' => route('stripe.cancel'),
                ]);
        } else {
            return redirect('/')->withErrors(['Invalid or expired link.']);
        }

    }

    public function stripeSuccess($userId) {

        $user = User::findOrFail($userId);
        if ($user) {
            $user->getUserProfile->is_subscribed = SubscriptionStatus::SUBSCRIBED;
            $user->getUserProfile->save();
            $token = Password::broker()->createToken($user);
            $resetLink = route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
            Helpers::sendMail(new BuyerCredentials($user->name, $user->email, $resetLink), $user->email);
        } else {
            return redirect('/')->withErrors(['Invalid or expired link.']);
        }
        return view('subscription.success', compact('user'));
    }

    public function stripeCancel() {
        return view('subscription.cancel');
    }

    public function buyerSubscriptionLink($userId) {
        $user = User::findOrFail($userId);
        if (!$user) {
            return redirect()->back()->withErrors(['Invalid or expired link.']);
        };
        $subscriptionLink = '';
        if ($user->getUserProfile->link_expires_at->isPast()) {
            $expiresAt = Carbon::now()->addHours(72);
            $token = Crypt::encryptString($user->id);
            $subscriptionLink = URL::temporarySignedRoute('buyer.subscription.pricing', $expiresAt, ['token' => $token]);
            $user->getUserProfile->subscription_link = $subscriptionLink;
            $user->getUserProfile->link_expires_at = $expiresAt;
            $user->getUserProfile->save();
        } else {
            $subscriptionLink = $user->getUserProfile->subscription_link;
        }
        Helpers::sendMail(new SubscriptionMail($user->name, $subscriptionLink), $user->email);
        return response()->json([
            'status' => true,
            'message' => 'Subscription link has been sent to buyer.'
        ]);

    }
}

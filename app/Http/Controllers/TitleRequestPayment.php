<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\TitleBuyerPaymentRequest;
use App\Mail\TitleRequestPaymentMail;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use \Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helpers;

class TitleRequestPayment extends Controller {
    public function createPaymentModal($orderId) {
        $order = Order::findOrFail($orderId);
        $currencies = Currency::orderBy('name')->get();
        return view('backend.titles. models.title-request-price', compact('order', 'currencies'))->render();
    }

    public function createPaymentLink(TitleBuyerPaymentRequest $request) {
        try {
            $order = Order::find($request->order_id);
            $buyer = $order->getBuyer;

            // Create or get Stripe Customer
            if (!$buyer->stripe_id) {
                $buyer->createAsStripeCustomer();
            }

            // Create Product and Price using Cashier's Stripe facade
            $product = Cashier::stripe()->products->create([
                'name' => 'Title Request #' . $order->order_uuid,
            ]);

            $price = Cashier::stripe()->prices->create([
                'product' => $product->id,
                'unit_amount' => (int)($request->amount * 100), // Convert to cents
                'currency' => $request->currency,
            ]);

            // Create Payment Link
            $paymentLink = Cashier::stripe()->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
                'customer_creation' => $buyer->stripe_id ? 'if_required' : 'always',
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => route('payment.success', [
                            'order_id' => $order->id,
                        ])
                    ]
                ],
                'custom_text' => [
                    'submit' => [
                        'message' => 'By clicking Pay, you agree to our terms and conditions.'
                    ]
                ],
                'payment_intent_data' => [
                    'metadata' => [
                        'order_id' => $order->id,
                        'payment_type' => WebhookController::SOURCE_PAYMENT_LINK,
                        'source' => WebhookController::SOURCE_TITLE_REQUEST
                    ]
                ]
            ]);

            // Store payment link details in order if needed
            $order->update([
                'payment_link' => $paymentLink->url,
                'total_price' => $request->amount,
                'currency' => $request->currency,
                'link_expires_at' => now()->addHours(72),
            ]);
            // Send email with payment link
            Helpers::sendMail(new TitleRequestPaymentMail($order), $buyer->email);

            return response()->json([
                'status' => true,
                'message' => 'Payment link created and sent successfully',
                'function' => 'closeTitleRequestPaymentModal',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating payment link: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handlePaymentSuccess(Request $request, $order_id) {
        $order = Order::findOrFail($order_id);
        $user = $order->getBuyer;
        if ($order->payment_status == PaymentStatus::Paid) {
            return view('buyer.title_request_payment.success', compact('order', 'user'));
        } else {
            return view('buyer.title_request_payment.failed', compact('order', 'user'));
        }
    }
}

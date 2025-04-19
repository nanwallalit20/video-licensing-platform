<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Enums\PaymentStatus;

class WebhookController extends CashierWebhookController {
    const SOURCE_TITLE_REQUEST = 'title_request';
    const SOURCE_PAYMENT_LINK = 'payment_link';

    /**
     * Handle payment_intent.succeeded webhook
     */
    protected function handlePaymentIntentSucceeded($payload) {
        \Log::info('Payment intent succeeded: ' . json_encode($payload));
        $paymentIntent = $payload['data']['object'];

        if ($this->isPaymentLinkTransaction($paymentIntent)) {
            $this->handleOrderPayment($paymentIntent);
        }
    }

    /**
     * Handle payment_intent.payment_failed webhook
     */
    protected function handlePaymentIntentPaymentFailed($payload) {
        $paymentIntent = $payload['data']['object'];

        if ($this->isPaymentLinkTransaction($paymentIntent)) {
            $this->handleFailedPayment($paymentIntent);
        }
    }

    private function isPaymentLinkTransaction($paymentIntent) {
        return isset($paymentIntent['metadata']['payment_type'])
            && $paymentIntent['metadata']['payment_type'] === self::SOURCE_PAYMENT_LINK
            && isset($paymentIntent['metadata']['source'])
            && $paymentIntent['metadata']['source'] === self::SOURCE_TITLE_REQUEST;
    }

    private function handleOrderPayment($paymentIntent) {
        $orderId = $paymentIntent['metadata']['order_id'];
        $order = Order::find($orderId);

        if (!$order) {
            \Log::error('Order not found for payment: ' . $paymentIntent['id']);
            return;
        }

        try {
            // Create payment transaction record
            OrderPayment::create([
                'order_id' => $orderId,
                'transaction_id' => $paymentIntent['id'],
                'payment_method' => $paymentIntent['payment_method_types'][0] ?? 'card',
                'amount_paid' => $paymentIntent['amount'] / 100,
                'currency' => strtoupper($paymentIntent['currency']),
                'payment_details' => $paymentIntent['charges']['data'][0]['receipt_url'] ?? null,
            ]);

            // Update order status
            $order->update([
                'payment_status' => PaymentStatus::Paid->value,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error processing payment webhook: ' . $e->getMessage());
        }
    }

    private function handleFailedPayment($paymentIntent) {
        $orderId = $paymentIntent['metadata']['order_id'];
        $order = Order::find($orderId);

        if (!$order) {
            \Log::error('Order not found for failed payment: ' . $paymentIntent['id']);
            return;
        }

        try {
            // Record failed transaction
            OrderPayment::create([
                'order_id' => $orderId,
                'transaction_id' => $paymentIntent['id'],
                'payment_method' => $paymentIntent['payment_method_types'][0] ?? 'card',
                'amount_paid' => 0,
                'currency' => strtoupper($paymentIntent['currency']),
                'payment_details' => $paymentIntent['last_payment_error'] ?? null,
            ]);

            // Update order status
            $order->update([
                'payment_status' => PaymentStatus::Failed->value,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error processing failed payment webhook: ' . $e->getMessage());
        }
    }
}

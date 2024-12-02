<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Stripe\Exception\SignatureVerificationException;
use App\Models\MasterUser;

class StripeWebhookController extends Controller
{
    //
    public function handleWebhook(Request $request)
    {
        // Set your Stripe secret key for webhooks
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        // Retrieve the raw POST data
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Handle different event types
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object; // contains a Stripe PaymentIntent object
                    $this->handleSuccessfulPayment($paymentIntent);
                    break;
                
                case 'invoice.payment_succeeded':
                    $invoice = $event->data->object; // contains a Stripe Invoice object
                    $this->handleSuccessfulInvoicePayment($invoice);
                    break;

                // You can add other event types here to handle different scenarios

                default:
                    // Handle unexpected events or log them
                    break;
            }

            // Respond with a 200 status to acknowledge receipt of the webhook
            return response('Webhook received', 200);

        } catch (SignatureVerificationException $e) {
            // If the signature verification fails, return a 400 error
            return response('Webhook signature verification failed.', 400);
        }
    }

    protected function handleSuccessfulPayment($paymentIntent)
    {
        // Process payment success, update user subscription, etc.
        $stripeId = $paymentIntent->customer;
        $user = MasterUser::where('stripe_id', $stripeId)->first();
        if ($user) {
            // Update user subscription status or handle payment success logic
            $user->update(['subscription_status' => 'active']);
        }
    }

    protected function handleSuccessfulInvoicePayment($invoice)
    {
        // $invoice = $event->data->object;

        // Access invoice details
        $customerEmail = $invoice->customer_email;
        $amountPaid = $invoice->amount_paid;
        $currency = $invoice->currency;

        // Handle invoice payment success (if using invoices)
        \Log::info('Invoice payment succeeded:', [
            'invoice_id' => $invoice->id,
            'customer_email' => $customerEmail,
            'amount_paid' => $amountPaid,
            'currency' => $currency,
        ]);
        $stripeId = $invoice->customer;
        $user = MasterUser::where('stripe_id', $stripeId)->first();
        if ($user) {
            // Process invoice payment success logic
            $user->update(['subscription_status' => 'active']);
        }
        
    }
   
}

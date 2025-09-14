<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $totalAmount * 100,
        'currency' => 'usd',
        'payment_method' => $_POST['payment_method_id']
    ]);
    
    if ($paymentIntent->status === 'succeeded') {
        // Proceed with order submission
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle error
}
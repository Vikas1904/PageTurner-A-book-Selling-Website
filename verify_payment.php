<?php
include 'config.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;

session_start();

$payment_id = $_GET['razorpay_payment_id'];
$order_id = $_GET['razorpay_order_id'];
$signature = $_GET['razorpay_signature'];

$user_id = $_SESSION['user_id'];

$api_key = "rzp_test_Xj6no3WEMAcBzo";
$api_secret = "pzmSybNSpY3bljSfSj1EXoh9";

$api = new Api($api_key, $api_secret);

try {
    $attributes = [
        'razorpay_order_id' => $order_id,
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $signature
    ];

    $api->utility->verifyPaymentSignature($attributes);

    // Update payment status in the orders table
    $update_query = "UPDATE `orders` SET payment_status = 'Completed' WHERE order_id = '$order_id' AND user_id = '$user_id'";
    mysqli_query($conn, $update_query) or die('Query failed');
    
    header("Location: orders.php?status=success");
    exit();

} catch (Exception $e) {
    header("Location: orders.php?status=failure");
    exit();
}
?>

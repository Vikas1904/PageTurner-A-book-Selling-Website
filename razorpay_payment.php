<?php
require('vendor/autoload.php');
use Razorpay\Api\Api;

session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = (string) $_SESSION['order_id']; // Cast to string to avoid errors
$total_amount = $_SESSION['total_amount'];

// Razorpay API Key and Secret
$api_key = "rzp_test_Xj6no3WEMAcBzo"; // Replace with your API Key
$api_secret = "pzmSybNSpY3bljSfSj1EXoh9"; // Replace with your API Secret

$api = new Api($api_key, $api_secret);

try {
    // Create a Razorpay order
    $razorpayOrder = $api->order->create([
        'receipt' => $order_id,
        'amount' => $total_amount * 100, // Amount in paise
        'currency' => 'INR'
    ]);

    // Store Razorpay order ID in session
    $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <script>
        var options = {
            "key": "<?php echo $api_key; ?>", // Your Razorpay API Key
            "amount": "<?php echo $total_amount * 100; ?>", // Amount in paise
            "currency": "INR",
            "name": "Your Store Name",
            "description": "Order Payment",
            "order_id": "<?php echo $_SESSION['razorpay_order_id']; ?>",
            "handler": function(response) {
                // Redirect to verify_payment.php with the payment response
                window.location.href = "verify_payment.php?razorpay_payment_id=" + response.razorpay_payment_id + "&razorpay_order_id=" + response.razorpay_order_id + "&razorpay_signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>",
                "email": "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>",
                "contact": "<?php echo isset($_SESSION['number']) ? $_SESSION['number'] : ''; ?>"
            },
            "theme": {
                "color": "#3399cc" // Customize your theme color
            }
        };
        
        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
</body>
</html>

<?php
session_start();

// blocks transaction if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

// gets total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        .box { width: 400px; padding: 20px; border: 1px solid #ccc; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 6px; margin-top: 3px; }
        .btn { margin-top: 15px; padding: 10px; width: 100%; background: black; color: white; border: none; }
    </style>
</head>
<body>

<h2>Checkout</h2>

<div class="box">
    <form action="process_order.php" method="POST">

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>City:</label>
        <input type="text" name="city" required>

        <label>State:</label>
        <input type="text" name="state" required>

        <label>ZIP Code:</label>
        <input type="text" name="zip" required>

        <label>Payment Method:</label>
        <select name="payment_method" required>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
            <option value="PayPal">PayPal</option>
        </select>

        <!-- order total -->
        <input type="hidden" name="order_total" value="<?php echo $total; ?>">

        <button type="submit" class="btn">Place Order ($<?php echo number_format($total, 2); ?>)</button>
    </form>
</div>

</body>
</html>

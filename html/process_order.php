<?php
// Connect to the database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "warehouse_db";

$conn = new mysqli($host, $user, $pass, $dbname);

// connection check
if ($conn->connect_error) {
    die("Database error: " . $conn->connect_error);
}

// Get shipping info
$fullName     = $_POST['fullName'];
$address1     = $_POST['addressLine1'];
$address2     = $_POST['addressLine2'];
$city         = $_POST['city'];
$state        = $_POST['state'];
$zip          = $_POST['zipCode'];
$country      = $_POST['country'];

// Get items from hidden fields
$products   = $_POST['productId'];
$quantities = $_POST['quantity'];
$locations  = $_POST['location'];

// Store order header
$orderSQL = "INSERT INTO orders (fullName, address1, address2, city, state, zipCode, country, orderDate)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($orderSQL);
$stmt->bind_param("sssssss", $fullName, $address1, $address2, $city, $state, $zip, $country);
$stmt->execute();

// Grab new order ID
$orderID = $stmt->insert_id;

// Insert each  item  and reduce the inventory
$itemSQL = "INSERT INTO order_items (orderID, productID, quantity, location)
            VALUES (?, ?, ?, ?)";
$itemStmt = $conn->prepare($itemSQL);

$updateSQL = "UPDATE inventory SET quantity = quantity - ? WHERE productID = ? AND location = ?";
$updateStmt = $conn->prepare($updateSQL);

// Loop through all items
foreach ($products as $i => $pid) {

    $qty = $quantities[$i];
    $loc = $locations[$i];

    // Insert  item
    $itemStmt->bind_param("iiis", $orderID, $pid, $qty, $loc);
    $itemStmt->execute();

    // Update stock
    $updateStmt->bind_param("iis", $qty, $pid, $loc);
    $updateStmt->execute();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Complete</title>
</head>
<body>

<h1>Order Submitted Successfully</h1>
<p>Your order number is <strong><?php echo $orderID; ?></strong></p>

<h2>Shipping To:</h2>
<p>
    <?php echo htmlspecialchars($fullName); ?><br>
    <?php echo htmlspecialchars($address1); ?><br>
    <?php if (!empty($address2)) echo htmlspecialchars($address2) . "<br>"; ?>
    <?php echo htmlspecialchars($city . ", " . $state . " " . $zip); ?><br>
    <?php echo htmlspecialchars($country); ?>
</p>

<h2>Items Ordered</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Location</th>
    </tr>

    <?php
    foreach ($products as $i => $pid) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($pid) . "</td>";
        echo "<td>" . htmlspecialchars($quantities[$i]) . "</td>";
        echo "<td>" . htmlspecialchars($locations[$i]) . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<br>
<button onclick="window.location.href='index.html'">Return to Home</button>
<button onclick="window.location.href='order_history.php'">View Order History</button>

</body>
</html>

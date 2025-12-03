<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}


$host = "localhost";
$user = "root";
$pass = "";
$db   = "cs4347_database";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get session values
$userId = (int) $_SESSION['userID'];
$role   = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

// === ADMIN CHECK ===
// user is admin ONLY IF role == "administrator"
$isAdmin = ($role === "administrator");


if ($isAdmin) {
    // Admin sees ALL orders
    $sql = "
        SELECT 
            o.orderID,
            o.userID,
            o.orderDate,
            o.deliveryDate,
            o.status
        FROM Orders o
        GROUP BY o.orderID
        ORDER BY o.orderDate DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    // Normal user sees ONLY their own orders
    $sql = "
        SELECT 
    o.orderID,
    o.userID,
    o.orderDate,
    o.deliveryDate,
    o.status,
    COALESCE(SUM(oc.quantity * p.unitPrice), 0) AS totalValue
FROM Orders o
LEFT JOIN OrderContents oc ON o.orderID = oc.orderID
LEFT JOIN Product p ON oc.productID = p.productID
WHERE o.userID = ?
GROUP BY o.orderID
ORDER BY o.orderDate DESC

    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
}

// SHOW SQL ERRORS IF ANY
if (!$result) {
    echo "<p><strong>SQL Error:</strong> " . htmlspecialchars($conn->error) . "</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order & Transaction History</title>

    
    <style>
        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }
        th {
            background: #f6f6f6;
        }
        .nav-btn {
            margin-bottom: 10px;
        }
        .nav-btn button {
            padding: 8px 14px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Order & Transaction History</h1>

    <div class="nav-btn">
        <button onclick="window.location.href='index.html'">Return to Home</button>
    </div>

<hr />

<h2>Recent Transactions</h2>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Type</th>
            <th>Order Date</th>
            <th>Delivery</th>
            <th>Status</th>
            <th>Total Value</th>
        </tr>
    </thead>

    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $type = "Standard";

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['orderID']) . "</td>";
                echo "<td>" . $type . "</td>";
                echo "<td>" . htmlspecialchars($row['orderDate']) . "</td>";
                echo "<td>" . htmlspecialchars($row['deliveryDate']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>$" . number_format((float)$row['totalValue'], 2) . "</td>";
                echo "</tr>";
            }

        } else {
            echo "<tr><td colspan='6' style='text-align:center; font-style:italic;'>No transactions found.</td></tr>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>
